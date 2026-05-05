<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * General list of all active orders, newest first.
     */
    public function index(Request $request)
    {
        $query = Order::active()->with('creator')->orderByDesc('order_date');

        // Filters
        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
        }
        if ($request->filled('customer_number')) {
            $query->where('customer_number', 'like', '%' . $request->customer_number . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('order_date', $request->date);
        }

        $orders   = $query->paginate(20)->withQueryString();
        $statuses = Order::STATUS_LABELS;

        return view('orders.index', compact('orders', 'statuses'));
    }

    /**
     * Show order creation form (Sales only).
     */
    public function create()
    {
        return view('orders.create');
    }

    /**
     * Store new order (Sales only).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'invoice_number'  => 'required|string|max:50|unique:orders,invoice_number',
            'customer_number' => 'required|string|max:50',
            'customer_name'   => 'required|string|max:255',
            'fiscal_data'     => 'nullable|string',
            'delivery_address'=> 'required|string',
            'notes'           => 'nullable|string',
        ]);

        $data['order_date'] = now();
        $data['status']     = Order::STATUS_ORDERED;
        $data['created_by'] = Auth::id();

        $order = Order::create($data);

        // Log initial status
        OrderStatusLog::create([
            'order_id'   => $order->id,
            'user_id'    => Auth::id(),
            'status'     => Order::STATUS_ORDERED,
            'notes'      => 'Pedido creado.',
            'changed_at' => now(),
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pedido creado correctamente.');
    }

    /**
     * Show order detail.
     */
    public function show(Order $order)
    {
        $order->load(['creator', 'statusLogs.user']);
        return view('orders.show', compact('order'));
    }

    /**
     * Show edit form (Sales, Admin).
     */
    public function edit(Order $order)
    {
        $statuses = Order::STATUS_LABELS;
        return view('orders.edit', compact('order', 'statuses'));
    }

    /**
     * Update order descriptive fields and/or status.
     */
    public function update(Request $request, Order $order)
    {
        $user = Auth::user();

        // ── Descriptive fields (Sales / Admin) ──────────────────────────────
        if ($request->has('invoice_number')) {
            $data = $request->validate([
                'invoice_number'   => 'required|string|max:50|unique:orders,invoice_number,' . $order->id,
                'customer_number'  => 'required|string|max:50',
                'customer_name'    => 'required|string|max:255',
                'fiscal_data'      => 'nullable|string',
                'delivery_address' => 'required|string',
                'notes'            => 'nullable|string',
            ]);
            $order->update($data);
            return redirect()->route('orders.show', $order)
                ->with('success', 'Pedido actualizado correctamente.');
        }

        // ── Block status/photo changes if archived ───────────────────────────
        if ($order->deleted && ($request->has('status') || $request->hasFile('load_photo') || $request->hasFile('delivery_photo'))) {
            return redirect()->route('orders.edit', $order)
                ->with('error', 'No se puede cambiar el estado ni subir fotos a un pedido archivado.');
        }

        // ── Status change ────────────────────────────────────────────────────
        if ($request->has('status')) {
            $newStatus = $request->input('status');

            abort_unless($order->canAdvanceStatus($user), 403, 'No puedes cambiar este estado.');
            abort_unless($order->getNextStatus() === $newStatus, 422, 'Transición de estado inválida.');

            $order->update(['status' => $newStatus]);

            OrderStatusLog::create([
                'order_id'   => $order->id,
                'user_id'    => $user->id,
                'status'     => $newStatus,
                'changed_at' => now(),
            ]);

            return redirect()->route('orders.show', $order)
                ->with('success', 'Estado actualizado a "' . Order::STATUS_LABELS[$newStatus] . '".');
        }

        // ── Photo upload ─────────────────────────────────────────────────────
        if ($request->hasFile('load_photo') || $request->hasFile('delivery_photo')) {
            abort_unless($user->isRole('Ruta'), 403, 'Solo el personal de Ruta puede subir fotos.');

            if ($request->hasFile('load_photo')) {
                $request->validate(['load_photo' => 'required|image|max:10240']);
                $path = $request->file('load_photo')->store('photos/load', 'public');
                $order->update(['load_photo' => $path]);
                return redirect()->route('orders.show', $order)
                    ->with('success', 'Foto de carga subida correctamente.');
            }

            if ($request->hasFile('delivery_photo')) {
                $request->validate(['delivery_photo' => 'required|image|max:10240']);
                $path = $request->file('delivery_photo')->store('photos/delivery', 'public');
                $order->update([
                    'delivery_photo' => $path,
                    'status'         => Order::STATUS_DELIVERED,
                ]);
                OrderStatusLog::create([
                    'order_id'   => $order->id,
                    'user_id'    => $user->id,
                    'status'     => Order::STATUS_DELIVERED,
                    'notes'      => 'Evidencia de entrega subida.',
                    'changed_at' => now(),
                ]);
                return redirect()->route('orders.show', $order)
                    ->with('success', 'Evidencia de entrega registrada. Estado: Entregado.');
            }
        }

        return redirect()->route('orders.show', $order);
    }

    /**
     * Logical (soft) delete — sets deleted = true.
     */
    public function destroy(Order $order)
    {
        $order->update(['deleted' => true]);
        return redirect()->route('orders.index')
            ->with('success', 'Pedido archivado correctamente.');
    }

    /**
     * List of logically deleted orders.
     */
    public function archived()
    {
        $orders = Order::archived()->with('creator')->orderByDesc('updated_at')->paginate(20);
        return view('orders.archived', compact('orders'));
    }

    /**
     * Restore a logically deleted order.
     */
    public function restore(Order $order)
    {
        $order->update(['deleted' => false]);
        return redirect()->route('orders.archived')
            ->with('success', 'Pedido restaurado correctamente.');
    }
}

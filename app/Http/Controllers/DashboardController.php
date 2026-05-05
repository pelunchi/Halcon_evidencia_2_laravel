<?php

namespace App\Http\Controllers;

use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'ordered'    => Order::active()->byStatus('ordered')->count(),
            'in_process' => Order::active()->byStatus('in_process')->count(),
            'in_route'   => Order::active()->byStatus('in_route')->count(),
            'delivered'  => Order::active()->byStatus('delivered')->count(),
            'total'      => Order::active()->count(),
            'archived'   => Order::archived()->count(),
        ];

        $recentOrders = Order::active()
            ->with('creator')
            ->orderByDesc('order_date')
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'recentOrders'));
    }
}

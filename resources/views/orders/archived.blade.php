@extends('layouts.app')
@section('title', 'Pedidos Archivados')

@section('content')
<div class="page-header">
    <h1>Pedidos Archivados</h1>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">← Regresar a Pedidos</a>
</div>

<div class="alert alert-warning">
    🗃 Estos pedidos han sido eliminados lógicamente. No son visibles en el portal público ni en la lista principal. Puedes editarlos o restaurarlos.
</div>

<table>
    <thead>
        <tr>
            <th>Factura</th><th># Cliente</th><th>Cliente</th><th>Fecha</th><th>Estado</th><th>Archivado</th><th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        <tr>
            <td><strong>{{ $order->invoice_number }}</strong></td>
            <td>{{ $order->customer_number }}</td>
            <td>{{ $order->customer_name }}</td>
            <td>{{ $order->order_date->format('d/m/Y') }}</td>
            <td><span class="badge badge-{{ $order->status }}">{{ $order->status_label }}</span></td>
            <td style="font-size:12px;color:#64748b">{{ $order->updated_at->format('d/m/Y H:i') }}</td>
            <td style="white-space:nowrap">
                <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary btn-sm">Ver</a>
                <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning btn-sm">Editar</a>
                <form method="POST" action="{{ route('orders.restore', $order) }}" style="display:inline"
                      onsubmit="return confirm('¿Restaurar este pedido?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm">↩ Restaurar</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;color:#64748b;padding:28px">No hay pedidos archivados.</td></tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top:16px">
    {{ $orders->links() }}
</div>
@endsection

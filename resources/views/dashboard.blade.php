@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="number" style="color:#92400e">{{ $stats['ordered'] }}</div>
        <div class="label">📥 Ordenados</div>
    </div>
    <div class="stat-card">
        <div class="number" style="color:#1e40af">{{ $stats['in_process'] }}</div>
        <div class="label">⚙️ En Proceso</div>
    </div>
    <div class="stat-card">
        <div class="number" style="color:#5b21b6">{{ $stats['in_route'] }}</div>
        <div class="label">🚛 En Ruta</div>
    </div>
    <div class="stat-card">
        <div class="number" style="color:#166534">{{ $stats['delivered'] }}</div>
        <div class="label">✅ Entregados</div>
    </div>
    <div class="stat-card">
        <div class="number">{{ $stats['total'] }}</div>
        <div class="label">📋 Total activos</div>
    </div>
    <div class="stat-card">
        <div class="number" style="color:#64748b">{{ $stats['archived'] }}</div>
        <div class="label">🗃 Archivados</div>
    </div>
</div>

<div class="card">
    <div class="card-title">
        Pedidos Recientes
        <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm" style="float:right">Ver todos →</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Factura</th><th># Cliente</th><th>Cliente</th><th>Fecha</th><th>Estado</th><th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentOrders as $order)
            <tr>
                <td><strong>{{ $order->invoice_number }}</strong></td>
                <td>{{ $order->customer_number }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->order_date->format('d/m/Y') }}</td>
                <td><span class="badge badge-{{ $order->status }}">{{ $order->status_label }}</span></td>
                <td><a href="{{ route('orders.show', $order) }}" class="btn btn-secondary btn-sm">Ver</a></td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#64748b;padding:24px">Sin pedidos registrados.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-top:8px">
    @if(auth()->user()->isRole('Ventas'))
    <a href="{{ route('orders.create') }}" class="btn btn-primary" style="text-align:center;padding:14px">➕ Nuevo Pedido</a>
    @endif
    @if(auth()->user()->hasAnyRole(['Admin','Ventas']))
    <a href="{{ route('orders.archived') }}" class="btn btn-secondary" style="text-align:center;padding:14px">🗃 Pedidos Archivados</a>
    @endif
    @if(auth()->user()->isAdmin())
    <a href="{{ route('users.index') }}" class="btn btn-secondary" style="text-align:center;padding:14px">👥 Gestionar Usuarios</a>
    @endif
</div>
@endsection

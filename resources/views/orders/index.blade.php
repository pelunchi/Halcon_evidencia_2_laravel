@extends('layouts.app')
@section('title', 'Pedidos')

@section('content')
<div class="page-header">
    <h1>Pedidos</h1>
    @if(auth()->user()->isRole('Ventas'))
        <a href="{{ route('orders.create') }}" class="btn btn-primary">➕ Nuevo Pedido</a>
    @endif
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('orders.index') }}">
    <div class="filters">
        <div class="form-group">
            <label>Factura</label>
            <input type="text" name="invoice_number" class="form-control"
                   value="{{ request('invoice_number') }}" placeholder="F-0001" style="width:140px"/>
        </div>
        <div class="form-group">
            <label># Cliente</label>
            <input type="text" name="customer_number" class="form-control"
                   value="{{ request('customer_number') }}" placeholder="C-101" style="width:130px"/>
        </div>
        <div class="form-group">
            <label>Estado</label>
            <select name="status" class="form-control" style="width:150px">
                <option value="">Todos</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ request('status')===$key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Fecha</label>
            <input type="date" name="date" class="form-control" value="{{ request('date') }}" style="width:160px"/>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary" style="margin-left:6px">Limpiar</a>
        </div>
    </div>
</form>

<table>
    <thead>
        <tr>
            <th>Factura</th><th># Cliente</th><th>Cliente</th><th>Fecha</th><th>Estado</th>
            <th>Creado por</th><th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        <tr>
            <td><strong>{{ $order->invoice_number }}</strong></td>
            <td>{{ $order->customer_number }}</td>
            <td>{{ $order->customer_name }}</td>
            <td>{{ $order->order_date->format('d/m/Y H:i') }}</td>
            <td><span class="badge badge-{{ $order->status }}">{{ $order->status_label }}</span></td>
            <td>{{ $order->creator->name ?? '—' }}</td>
            <td style="white-space:nowrap">
                <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary btn-sm">Ver</a>
                @if(auth()->user()->hasAnyRole(['Admin','Ventas','Almacen','Ruta']))
                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning btn-sm">Editar</a>
                @endif
                @if(auth()->user()->hasAnyRole(['Admin','Ventas']))
                    <form method="POST" action="{{ route('orders.destroy', $order) }}" style="display:inline"
                          onsubmit="return confirm('¿Archivar este pedido?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Archivar</button>
                    </form>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;color:#64748b;padding:28px">Sin pedidos con esos filtros.</td></tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top:16px">
    {{ $orders->links() }}
</div>
@endsection

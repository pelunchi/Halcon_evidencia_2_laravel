@extends('layouts.app')
@section('title', 'Editar Pedido ' . $order->invoice_number)

@section('content')
<div class="page-header">
    <h1>Editar Pedido — {{ $order->invoice_number }}</h1>
    @if($order->deleted)
        <a href="{{ route('orders.archived') }}" class="btn btn-secondary">← Regresar a Archivados</a>
    @else
        <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">← Regresar</a>
    @endif
</div>

@if($order->deleted)
    <div class="alert alert-warning">
        ⚠️ Este pedido está <strong>archivado</strong>. Puedes editar sus datos, pero no puedes cambiar el estado ni subir fotos hasta que lo restaures.
    </div>
@endif
<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start">

    {{-- Edit descriptive fields (Sales / Admin) --}}
    @if(auth()->user()->hasAnyRole(['Ventas','Admin']))
    <div class="card">
        <div class="card-title">Datos del Pedido</div>
        <form method="POST" action="{{ route('orders.update', $order) }}">
            @csrf @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label class="lbl">Número de Factura *</label>
                    <input type="text" name="invoice_number" class="form-control {{ $errors->has('invoice_number') ? 'is-invalid' : '' }}"
                           value="{{ old('invoice_number', $order->invoice_number) }}" required/>
                    @error('invoice_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="lbl">Número de Cliente *</label>
                    <input type="text" name="customer_number" class="form-control {{ $errors->has('customer_number') ? 'is-invalid' : '' }}"
                           value="{{ old('customer_number', $order->customer_number) }}" required/>
                    @error('customer_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="lbl">Nombre / Razón Social *</label>
                <input type="text" name="customer_name" class="form-control {{ $errors->has('customer_name') ? 'is-invalid' : '' }}"
                       value="{{ old('customer_name', $order->customer_name) }}" required/>
                @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="lbl">Datos Fiscales</label>
                <textarea name="fiscal_data" class="form-control" rows="3">{{ old('fiscal_data', $order->fiscal_data) }}</textarea>
            </div>

            <div class="form-group">
                <label class="lbl">Dirección de Entrega *</label>
                <textarea name="delivery_address" class="form-control {{ $errors->has('delivery_address') ? 'is-invalid' : '' }}"
                          rows="2" required>{{ old('delivery_address', $order->delivery_address) }}</textarea>
                @error('delivery_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="lbl">Notas</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes', $order->notes) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
    @endif

    {{-- Right: status change + photo upload --}}
    <div>
        {{-- Status change (Almacen) --}}
        @if(auth()->user()->isRole('Almacen') && $order->canAdvanceStatus(auth()->user()) && $order->getNextStatus())
        <div class="card">
            <div class="card-title">Cambiar Estado</div>
            <p style="font-size:13px;color:#64748b;margin-bottom:14px">
                Estado actual: <span class="badge badge-{{ $order->status }}">{{ $order->status_label }}</span>
            </p>
            <form method="POST" action="{{ route('orders.update', $order) }}">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="{{ $order->getNextStatus() }}"/>
                <button type="submit" class="btn btn-primary" style="width:100%"
                        onclick="return confirm('¿Cambiar estado a {{ \App\Models\Order::STATUS_LABELS[$order->getNextStatus()] }}?')">
                    ⬆️ Cambiar a {{ \App\Models\Order::STATUS_LABELS[$order->getNextStatus()] }}
                </button>
            </form>
        </div>
        @endif

        {{-- Photo upload (Ruta) --}}
        @if(auth()->user()->isRole('Ruta') && $order->status === 'in_route')
        <div class="card">
            <div class="card-title">Subir Fotografías</div>

            @if(!$order->load_photo)
            <div class="form-group">
                <label class="lbl">📦 Foto de Carga de Unidad</label>
                <form method="POST" action="{{ route('orders.update', $order) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <input type="file" name="load_photo" accept="image/*" class="form-control" required/>
                    @error('load_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <button type="submit" class="btn btn-warning" style="margin-top:8px;width:100%">
                        📸 Subir foto de carga
                    </button>
                </form>
            </div>
            @else
            <div class="alert alert-success" style="margin-bottom:14px">✓ Foto de carga ya registrada.</div>
            @endif

            @if(!$order->delivery_photo)
            <div class="form-group">
                <label class="lbl">✅ Evidencia de Entrega</label>
                <form method="POST" action="{{ route('orders.update', $order) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <input type="file" name="delivery_photo" accept="image/*" class="form-control" required/>
                    @error('delivery_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <button type="submit" class="btn btn-success" style="margin-top:8px;width:100%"
                            onclick="return confirm('¿Confirmas la entrega? El estado cambiará a Entregado.')">
                        ✅ Subir evidencia y marcar entregado
                    </button>
                </form>
            </div>
            @else
            <div class="alert alert-success">✓ Evidencia de entrega ya registrada.</div>
            @endif
        </div>
        @endif

        <div style="margin-top:8px;display:flex;flex-direction:column;gap:8px">
            <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary" style="width:100%;text-align:center;display:block">
                👁 Ver detalle del pedido
            </a>
            @if($order->deleted)
            <form method="POST" action="{{ route('orders.restore', $order) }}"
                  onsubmit="return confirm('¿Restaurar este pedido? Volverá a ser visible en la lista principal.')">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-success" style="width:100%">↩ Restaurar Pedido</button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection

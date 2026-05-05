@extends('layouts.app')
@section('title', 'Nuevo Pedido')

@section('content')
<div class="page-header">
    <h1>Nuevo Pedido</h1>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">← Regresar</a>
</div>

<div class="card" style="max-width:680px">
    <form method="POST" action="{{ route('orders.store') }}">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label class="lbl">Número de Factura *</label>
                <input type="text" name="invoice_number" class="form-control {{ $errors->has('invoice_number') ? 'is-invalid' : '' }}"
                       value="{{ old('invoice_number') }}" placeholder="F-0007" required/>
                @error('invoice_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="lbl">Número de Cliente *</label>
                <input type="text" name="customer_number" class="form-control {{ $errors->has('customer_number') ? 'is-invalid' : '' }}"
                       value="{{ old('customer_number') }}" placeholder="C-101" required/>
                @error('customer_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group">
            <label class="lbl">Nombre / Razón Social *</label>
            <input type="text" name="customer_name" class="form-control {{ $errors->has('customer_name') ? 'is-invalid' : '' }}"
                   value="{{ old('customer_name') }}" placeholder="Empresa Constructora S.A." required/>
            @error('customer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="lbl">Datos Fiscales (RFC, dirección fiscal)</label>
            <textarea name="fiscal_data" class="form-control {{ $errors->has('fiscal_data') ? 'is-invalid' : '' }}"
                      rows="3" placeholder="RFC: ABC123456XY0&#10;Calle, Col., CP, Ciudad">{{ old('fiscal_data') }}</textarea>
            @error('fiscal_data')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="lbl">Dirección de Entrega *</label>
            <textarea name="delivery_address" class="form-control {{ $errors->has('delivery_address') ? 'is-invalid' : '' }}"
                      rows="2" placeholder="Calle, número, colonia, ciudad" required>{{ old('delivery_address') }}</textarea>
            @error('delivery_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="lbl">Notas / Instrucciones Extra</label>
            <textarea name="notes" class="form-control" rows="2"
                      placeholder="Instrucciones especiales, referencias, etc.">{{ old('notes') }}</textarea>
        </div>

        <div style="display:flex;gap:10px;margin-top:6px">
            <button type="submit" class="btn btn-primary">Guardar Pedido</button>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection

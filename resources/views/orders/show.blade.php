@extends('layouts.app')
@section('title', 'Pedido ' . $order->invoice_number)

@section('content')
<div class="page-header">
    <h1>
        {{ $order->invoice_number }}
        <span class="badge badge-{{ $order->status }}" style="font-size:14px;margin-left:10px">{{ $order->status_label }}</span>
    </h1>
    <div style="display:flex;gap:8px">
        @if(auth()->user()->hasAnyRole(['Admin','Ventas','Almacen','Ruta']) && !$order->deleted)
            <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning">✏️ Editar</a>
        @endif
        @if(auth()->user()->hasAnyRole(['Admin','Ventas']) && !$order->deleted)
            <form method="POST" action="{{ route('orders.destroy', $order) }}"
                  onsubmit="return confirm('¿Archivar este pedido?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger">🗃 Archivar</button>
            </form>
        @endif
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">← Regresar</a>
    </div>
</div>

@if($order->deleted)
    <div class="alert alert-warning">⚠️ Este pedido está archivado y no es visible en el portal público.</div>
@endif

<div class="detail-grid">
    {{-- Left column: order info --}}
    <div>
        <div class="card">
            <div class="card-title">Información del Pedido</div>
            <div class="detail-row"><div class="key">Factura</div><div>{{ $order->invoice_number }}</div></div>
            <div class="detail-row"><div class="key"># Cliente</div><div>{{ $order->customer_number }}</div></div>
            <div class="detail-row"><div class="key">Cliente</div><div>{{ $order->customer_name }}</div></div>
            <div class="detail-row"><div class="key">Fecha</div><div>{{ $order->order_date->format('d/m/Y H:i') }}</div></div>
            <div class="detail-row"><div class="key">Dirección</div><div>{{ $order->delivery_address }}</div></div>
            <div class="detail-row"><div class="key">Datos Fiscales</div><div style="white-space:pre-line">{{ $order->fiscal_data ?: '—' }}</div></div>
            <div class="detail-row"><div class="key">Notas</div><div>{{ $order->notes ?: '—' }}</div></div>
            <div class="detail-row"><div class="key">Creado por</div><div>{{ $order->creator->name ?? '—' }}</div></div>
        </div>

        {{-- Photos --}}
        <div class="card">
            <div class="card-title">Fotografías</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                {{-- Load photo --}}
                <div>
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#64748b;margin-bottom:8px">
                        📦 Carga de Unidad
                    </div>
                    @if($order->load_photo)
                        <img src="{{ Storage::url($order->load_photo) }}" alt="Foto de carga"
                             style="width:100%;border-radius:8px;border:1px solid #e2e8f0"/>
                    @else
                        <div style="height:110px;background:#f8fafc;border:2px dashed #e2e8f0;border-radius:8px;
                                    display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:13px;flex-direction:column;gap:6px">
                            <span style="font-size:24px">📷</span>Sin foto
                        </div>
                        @if(auth()->user()->isRole('Ruta') && $order->status === 'in_route' && !$order->deleted)
                            <form method="POST" action="{{ route('orders.update', $order) }}"
                                  enctype="multipart/form-data" style="margin-top:8px">
                                @csrf @method('PUT')
                                <input type="file" name="load_photo" accept="image/*" class="form-control"
                                       style="font-size:12px" required/>
                                <button type="submit" class="btn btn-warning btn-sm" style="margin-top:6px;width:100%">
                                    📸 Subir foto de carga
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
                {{-- Delivery photo --}}
                <div>
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#64748b;margin-bottom:8px">
                        ✅ Evidencia de Entrega
                    </div>
                    @if($order->delivery_photo)
                        <img src="{{ Storage::url($order->delivery_photo) }}" alt="Evidencia de entrega"
                             style="width:100%;border-radius:8px;border:1px solid #e2e8f0"/>
                    @else
                        <div style="height:110px;background:#f8fafc;border:2px dashed #e2e8f0;border-radius:8px;
                                    display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:13px;flex-direction:column;gap:6px">
                            <span style="font-size:24px">📷</span>Sin foto
                        </div>
                        @if(auth()->user()->isRole('Ruta') && $order->status === 'in_route' && !$order->deleted)
                            <form method="POST" action="{{ route('orders.update', $order) }}"
                                  enctype="multipart/form-data" style="margin-top:8px">
                                @csrf @method('PUT')
                                <input type="file" name="delivery_photo" accept="image/*" class="form-control"
                                       style="font-size:12px" required/>
                                <button type="submit" class="btn btn-success btn-sm" style="margin-top:6px;width:100%"
                                        onclick="return confirm('¿Confirmas la entrega? El estado cambiará a Entregado.')">
                                    ✅ Subir evidencia y marcar entregado
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Right column: status + timeline --}}
    <div>
        {{-- Status card --}}
        <div class="card">
            <div class="card-title">Estado del Pedido</div>

            {{-- Advance status button --}}
            @if(!$order->deleted && $order->canAdvanceStatus(auth()->user()) && $order->getNextStatus())
                <form method="POST" action="{{ route('orders.update', $order) }}" style="margin-bottom:16px">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="{{ $order->getNextStatus() }}"/>
                    <button type="submit" class="btn btn-primary" style="width:100%"
                            onclick="return confirm('¿Cambiar estado a {{ \App\Models\Order::STATUS_LABELS[$order->getNextStatus()] }}?')">
                        ⬆️ Cambiar a {{ \App\Models\Order::STATUS_LABELS[$order->getNextStatus()] }}
                    </button>
                </form>
            @endif

            {{-- Timeline --}}
            <ul class="timeline">
                @php
                    $sequence = \App\Models\Order::STATUS_SEQUENCE;
                    $curIdx   = array_search($order->status, $sequence);
                @endphp
                @foreach(\App\Models\Order::STATUS_LABELS as $key => $label)
                    @php
                        $idx  = array_search($key, $sequence);
                        $done = $idx <= $curIdx;
                        $log  = $order->statusLogs->firstWhere('status', $key);
                    @endphp
                    <li>
                        <div class="timeline-dot {{ $done ? 'done' : '' }}">{{ $done ? '✓' : ($idx+1) }}</div>
                        <div class="timeline-body">
                            <strong>{{ $label }}</strong>
                            @if($done && $log)
                                <small>{{ $log->changed_at->format('d/m/Y H:i') }} — {{ $log->user->name }}</small>
                            @else
                                <small style="color:#94a3b8">Pendiente</small>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Status log table --}}
        @if($order->statusLogs->count())
        <div class="card">
            <div class="card-title">Bitácora de Cambios</div>
            <table>
                <thead>
                    <tr><th>Estado</th><th>Usuario</th><th>Fecha</th></tr>
                </thead>
                <tbody>
                    @foreach($order->statusLogs as $log)
                    <tr>
                        <td><span class="badge badge-{{ $log->status }}">{{ $log->status_label }}</span></td>
                        <td>{{ $log->user->name }}</td>
                        <td>{{ $log->changed_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection

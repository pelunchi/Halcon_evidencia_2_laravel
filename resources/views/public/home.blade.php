<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>HALCON — Rastrear Pedido</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:Arial,sans-serif;background:#f4f6f8;min-height:100vh}
        header{background:#1e293b;padding:14px 32px;display:flex;justify-content:space-between;align-items:center}
        header .brand{font-size:22px;font-weight:900;color:#f59e0b;letter-spacing:2px}
        header .brand small{font-size:10px;color:#64748b;letter-spacing:2px;display:block}
        header a{color:#94a3b8;font-size:13px;text-decoration:none}
        header a:hover{color:#f1f5f9}
        .hero{max-width:520px;margin:60px auto 0;padding:0 20px}
        h2{font-size:28px;font-weight:900;color:#1e293b;margin-bottom:6px}
        .sub{color:#64748b;font-size:14px;margin-bottom:28px}
        .card{background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,.06)}
        .form-group{margin-bottom:16px}
        label{display:block;font-size:11px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.6px;margin-bottom:5px}
        input{width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:6px;font-size:14px}
        input:focus{outline:none;border-color:#2563eb}
        .is-invalid{border-color:#dc2626}
        .invalid-feedback{color:#dc2626;font-size:12px;margin-top:4px}
        .btn{width:100%;padding:11px;background:#2563eb;color:#fff;border:none;border-radius:6px;font-size:15px;font-weight:700;cursor:pointer;margin-top:6px}
        .btn:hover{background:#1d4ed8}
        .result{margin-top:22px}
        .result-card{background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:22px;box-shadow:0 2px 12px rgba(0,0,0,.06)}
        .status-row{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px;flex-wrap:wrap;gap:8px}
        .order-name{font-size:18px;font-weight:800;color:#1e293b}
        .order-meta{font-size:12px;color:#64748b;margin-top:2px}
        .badge{display:inline-block;padding:4px 12px;border-radius:12px;font-size:12px;font-weight:700}
        .badge-ordered    {background:#fef3c7;color:#92400e}
        .badge-in_process {background:#dbeafe;color:#1e40af}
        .badge-in_route   {background:#ede9fe;color:#5b21b6}
        .badge-delivered  {background:#dcfce7;color:#166534}
        .info-row{font-size:13px;color:#475569;margin-bottom:6px}
        .info-row span{color:#64748b}
        .photo-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#f59e0b;margin:16px 0 8px}
        .photo-label::before{content:'📸 '}
        img.evidence{width:100%;border-radius:8px;border:1px solid #e2e8f0;margin-top:4px}
        .not-found{background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;padding:14px;color:#991b1b;font-size:14px;margin-top:18px}

        /* Timeline for status */
        .timeline{margin-top:16px;padding-top:14px;border-top:1px solid #f1f5f9}
        .timeline h4{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#64748b;margin-bottom:12px}
        .tl-item{display:flex;gap:10px;padding-bottom:12px;position:relative}
        .tl-item:not(:last-child)::before{content:'';position:absolute;left:11px;top:22px;bottom:0;width:2px;background:#e2e8f0}
        .tl-dot{width:22px;height:22px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700}
        .tl-dot.done{background:#2563eb;color:#fff}
        .tl-dot.pending{background:#e2e8f0;color:#94a3b8}
        .tl-body strong{display:block;font-size:13px;color:#1e293b}
        .tl-body small{font-size:11px;color:#64748b}
    </style>
</head>
<body>
<header>
    <div class="brand">HALCON<small>MATERIALES</small></div>
    <a href="{{ route('login') }}">Acceso empleados →</a>
</header>

<div class="hero">
    <h2>Rastrear Pedido</h2>
    <p class="sub">Ingresa tu número de cliente y número de factura para consultar el estado de tu pedido.</p>

    <div class="card">
        <form method="POST" action="{{ route('public.search') }}">
            @csrf
            <div class="form-group">
                <label>Número de Cliente</label>
                <input type="text" name="customer_number"
                       value="{{ old('customer_number', $customer_number ?? '') }}"
                       placeholder="Ej: C-101"
                       class="{{ $errors->has('customer_number') ? 'is-invalid' : '' }}" required/>
                @error('customer_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Número de Factura</label>
                <input type="text" name="invoice_number"
                       value="{{ old('invoice_number', $invoice_number ?? '') }}"
                       placeholder="Ej: F-0001"
                       class="{{ $errors->has('invoice_number') ? 'is-invalid' : '' }}" required/>
                @error('invoice_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn">CONSULTAR PEDIDO</button>
        </form>
    </div>

    {{-- Results --}}
    @if(isset($searched))
        <div class="result">
            @if($order)
                <div class="result-card">
                    <div class="status-row">
                        <div>
                            <div class="order-name">{{ $order->customer_name }}</div>
                            <div class="order-meta">Factura: {{ $order->invoice_number }} &nbsp;·&nbsp; Cliente: {{ $order->customer_number }}</div>
                        </div>
                        <span class="badge badge-{{ $order->status }}">{{ $order->status_label }}</span>
                    </div>

                    <div class="info-row">📅 <span>Fecha del pedido:</span> {{ $order->order_date->format('d/m/Y H:i') }}</div>
                    <div class="info-row">📍 <span>Dirección de entrega:</span> {{ $order->delivery_address }}</div>

                    {{-- Status timeline --}}
                    <div class="timeline">
                        <h4>Historial de estado</h4>
                        @php
                            $statuses = \App\Models\Order::STATUS_SEQUENCE;
                            $curIdx   = array_search($order->status, $statuses);
                        @endphp
                        @foreach(\App\Models\Order::STATUS_LABELS as $key => $label)
                            @php $idx = array_search($key, $statuses); $done = $idx <= $curIdx; @endphp
                            <div class="tl-item">
                                <div class="tl-dot {{ $done ? 'done' : 'pending' }}">{{ $done ? '✓' : ($idx+1) }}</div>
                                <div class="tl-body">
                                    <strong>{{ $label }}</strong>
                                    @if($done)
                                        @php $log = $order->statusLogs->firstWhere('status', $key); @endphp
                                        @if($log)
                                            <small>{{ $log->changed_at->format('d/m/Y H:i') }} — {{ $log->user->name }}</small>
                                        @endif
                                    @else
                                        <small>Pendiente</small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Delivery photo --}}
                    @if($order->status === 'delivered' && $order->delivery_photo)
                        <div class="photo-label">Evidencia de Entrega</div>
                        <img class="evidence" src="{{ Storage::url($order->delivery_photo) }}" alt="Evidencia de entrega"/>
                    @endif
                </div>
            @else
                <div class="not-found">
                    ❌ No se encontró ningún pedido activo con esos datos. Verifica tu número de cliente y factura.
                </div>
            @endif
        </div>
    @endif
</div>
</body>
</html>

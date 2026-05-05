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
    <a href="<?php echo e(route('login')); ?>">Acceso empleados →</a>
</header>

<div class="hero">
    <h2>Rastrear Pedido</h2>
    <p class="sub">Ingresa tu número de cliente y número de factura para consultar el estado de tu pedido.</p>

    <div class="card">
        <form method="POST" action="<?php echo e(route('public.search')); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label>Número de Cliente</label>
                <input type="text" name="customer_number"
                       value="<?php echo e(old('customer_number', $customer_number ?? '')); ?>"
                       placeholder="Ej: C-101"
                       class="<?php echo e($errors->has('customer_number') ? 'is-invalid' : ''); ?>" required/>
                <?php $__errorArgs = ['customer_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label>Número de Factura</label>
                <input type="text" name="invoice_number"
                       value="<?php echo e(old('invoice_number', $invoice_number ?? '')); ?>"
                       placeholder="Ej: F-0001"
                       class="<?php echo e($errors->has('invoice_number') ? 'is-invalid' : ''); ?>" required/>
                <?php $__errorArgs = ['invoice_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <button type="submit" class="btn">CONSULTAR PEDIDO</button>
        </form>
    </div>

    
    <?php if(isset($searched)): ?>
        <div class="result">
            <?php if($order): ?>
                <div class="result-card">
                    <div class="status-row">
                        <div>
                            <div class="order-name"><?php echo e($order->customer_name); ?></div>
                            <div class="order-meta">Factura: <?php echo e($order->invoice_number); ?> &nbsp;·&nbsp; Cliente: <?php echo e($order->customer_number); ?></div>
                        </div>
                        <span class="badge badge-<?php echo e($order->status); ?>"><?php echo e($order->status_label); ?></span>
                    </div>

                    <div class="info-row">📅 <span>Fecha del pedido:</span> <?php echo e($order->order_date->format('d/m/Y H:i')); ?></div>
                    <div class="info-row">📍 <span>Dirección de entrega:</span> <?php echo e($order->delivery_address); ?></div>

                    
                    <div class="timeline">
                        <h4>Historial de estado</h4>
                        <?php
                            $statuses = \App\Models\Order::STATUS_SEQUENCE;
                            $curIdx   = array_search($order->status, $statuses);
                        ?>
                        <?php $__currentLoopData = \App\Models\Order::STATUS_LABELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $idx = array_search($key, $statuses); $done = $idx <= $curIdx; ?>
                            <div class="tl-item">
                                <div class="tl-dot <?php echo e($done ? 'done' : 'pending'); ?>"><?php echo e($done ? '✓' : ($idx+1)); ?></div>
                                <div class="tl-body">
                                    <strong><?php echo e($label); ?></strong>
                                    <?php if($done): ?>
                                        <?php $log = $order->statusLogs->firstWhere('status', $key); ?>
                                        <?php if($log): ?>
                                            <small><?php echo e($log->changed_at->format('d/m/Y H:i')); ?> — <?php echo e($log->user->name); ?></small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <small>Pendiente</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <?php if($order->status === 'delivered' && $order->delivery_photo): ?>
                        <div class="photo-label">Evidencia de Entrega</div>
                        <img class="evidence" src="<?php echo e(Storage::url($order->delivery_photo)); ?>" alt="Evidencia de entrega"/>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="not-found">
                    ❌ No se encontró ningún pedido activo con esos datos. Verifica tu número de cliente y factura.
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\WebDesing\halcon_final\resources\views/public/home.blade.php ENDPATH**/ ?>
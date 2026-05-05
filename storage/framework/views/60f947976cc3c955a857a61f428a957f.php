<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>HALCON — <?php echo $__env->yieldContent('title', 'Panel'); ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 14px; background: #f4f6f8; color: #333; display: flex; min-height: 100vh; }
        a { color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }

        /* Sidebar */
        .sidebar { width: 220px; background: #1e293b; color: #cbd5e1; min-height: 100vh; flex-shrink: 0; display: flex; flex-direction: column; }
        .sidebar-brand { padding: 20px 18px; font-size: 22px; font-weight: 900; color: #f59e0b; letter-spacing: 2px; border-bottom: 1px solid #334155; }
        .sidebar-brand small { display: block; font-size: 10px; color: #64748b; font-weight: 400; letter-spacing: 2px; }
        .sidebar-user { padding: 14px 18px; border-bottom: 1px solid #334155; font-size: 13px; }
        .sidebar-user strong { display: block; color: #f1f5f9; }
        .sidebar-user span { font-size: 11px; background: #334155; padding: 2px 8px; border-radius: 10px; display: inline-block; margin-top: 4px; }
        .sidebar nav { flex: 1; padding: 10px 10px; }
        .sidebar nav a { display: block; padding: 9px 12px; border-radius: 6px; color: #94a3b8; font-size: 13px; margin-bottom: 2px; }
        .sidebar nav a:hover, .sidebar nav a.active { background: #334155; color: #f1f5f9; text-decoration: none; }
        .sidebar-footer { padding: 14px 18px; border-top: 1px solid #334155; }
        .sidebar-footer form button { background: none; color: #94a3b8; font-size: 13px; cursor: pointer; border: none; padding: 0; }
        .sidebar-footer form button:hover { color: #f1f5f9; }

        /* Main */
        .main { flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .topbar { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 14px 28px; font-size: 16px; font-weight: 700; color: #1e293b; }
        .content { padding: 28px; flex: 1; }

        /* Alerts */
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 18px; font-size: 13px; }
        .alert-success { background: #dcfce7; border: 1px solid #86efac; color: #166534; }
        .alert-error   { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }
        .alert-warning { background: #fef9c3; border: 1px solid #fde047; color: #854d0e; }

        /* Tables */
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.07); }
        th { background: #f8fafc; padding: 11px 14px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: .6px; color: #64748b; border-bottom: 1px solid #e2e8f0; }
        td { padding: 11px 14px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8fafc; }

        /* Cards */
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 22px; margin-bottom: 20px; }
        .card-title { font-size: 16px; font-weight: 700; margin-bottom: 16px; color: #1e293b; }

        /* Buttons */
        .btn { display: inline-block; padding: 8px 18px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; }
        .btn:hover { text-decoration: none; filter: brightness(.95); }
        .btn-primary  { background: #2563eb; color: #fff; }
        .btn-success  { background: #16a34a; color: #fff; }
        .btn-warning  { background: #d97706; color: #fff; }
        .btn-danger   { background: #dc2626; color: #fff; }
        .btn-secondary{ background: #e2e8f0; color: #475569; }
        .btn-sm { padding: 5px 12px; font-size: 12px; }

        /* Forms */
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px; }
        .form-control { width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; background: #fff; color: #333; }
        .form-control:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 2px #bfdbfe; }
        .form-control.is-invalid { border-color: #dc2626; }
        .invalid-feedback { color: #dc2626; font-size: 12px; margin-top: 4px; }
        .form-check { display: flex; align-items: center; gap: 8px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        /* Badges */
        .badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; }
        .badge-ordered    { background: #fef3c7; color: #92400e; }
        .badge-in_process { background: #dbeafe; color: #1e40af; }
        .badge-in_route   { background: #ede9fe; color: #5b21b6; }
        .badge-delivered  { background: #dcfce7; color: #166534; }
        .badge-active     { background: #dcfce7; color: #166534; }
        .badge-inactive   { background: #fee2e2; color: #991b1b; }

        /* Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card  { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px; text-align: center; }
        .stat-card .number { font-size: 36px; font-weight: 900; color: #1e293b; }
        .stat-card .label  { font-size: 12px; color: #64748b; margin-top: 4px; }

        /* Filters */
        .filters { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin-bottom: 18px; display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; }
        .filters .form-group { margin-bottom: 0; min-width: 160px; }

        /* Page title row */
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; }
        .page-header h1 { font-size: 22px; font-weight: 800; color: #1e293b; }

        /* Timeline */
        .timeline { list-style: none; padding: 0; }
        .timeline li { display: flex; gap: 12px; padding-bottom: 14px; position: relative; }
        .timeline li:not(:last-child)::before { content:''; position: absolute; left: 11px; top: 24px; bottom: 0; width: 2px; background: #e2e8f0; }
        .timeline-dot { width: 24px; height: 24px; border-radius: 50%; background: #e2e8f0; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: #64748b; }
        .timeline-dot.done { background: #2563eb; color: #fff; }
        .timeline-body strong { display: block; font-size: 13px; }
        .timeline-body small  { color: #64748b; font-size: 12px; }

        /* Order detail */
        .detail-grid { display: grid; grid-template-columns: 1fr 340px; gap: 24px; }
        .detail-row { display: grid; grid-template-columns: 140px 1fr; gap: 8px; padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .detail-row .key { color: #64748b; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; padding-top: 2px; }
        @media(max-width:900px){ .detail-grid{ grid-template-columns:1fr; } .form-row{ grid-template-columns:1fr; } }
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-brand">HALCON<small>MATERIALES</small></div>
    <div class="sidebar-user">
        <strong><?php echo e(auth()->user()->name); ?></strong>
        <span><?php echo e(auth()->user()->role_label); ?></span>
    </div>
    <nav>
        <a href="<?php echo e(route('dashboard')); ?>" class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">📊 Dashboard</a>
        <a href="<?php echo e(route('orders.index')); ?>" class="<?php echo e(request()->routeIs('orders.*') && !request()->routeIs('orders.archived') ? 'active' : ''); ?>">📋 Pedidos</a>
        <?php if(auth()->check() && auth()->user()->hasAnyRole(['Admin','Ventas'])): ?>
        <a href="<?php echo e(route('orders.archived')); ?>" class="<?php echo e(request()->routeIs('orders.archived') ? 'active' : ''); ?>">🗃 Archivados</a>
        <?php endif; ?>
        <?php if(auth()->check() && auth()->user()->hasAnyRole(['Admin'])): ?>
        <a href="<?php echo e(route('users.index')); ?>" class="<?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>">👥 Usuarios</a>
        <?php endif; ?>
    </nav>
    <div class="sidebar-footer">
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit">🚪 Cerrar sesión</button>
        </form>
    </div>
</aside>

<div class="main">
    <div class="topbar"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></div>
    <div class="content">
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-error"><?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="alert alert-error">
                <strong>Por favor corrige los siguientes errores:</strong>
                <ul style="margin-top:6px;padding-left:18px">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </div>
</div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\WebDesing\halcon_final\resources\views/layouts/app.blade.php ENDPATH**/ ?>
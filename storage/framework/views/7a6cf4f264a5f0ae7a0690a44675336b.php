<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="stats-grid">
    <div class="stat-card">
        <div class="number" style="color:#92400e"><?php echo e($stats['ordered']); ?></div>
        <div class="label">📥 Ordenados</div>
    </div>
    <div class="stat-card">
        <div class="number" style="color:#1e40af"><?php echo e($stats['in_process']); ?></div>
        <div class="label">⚙️ En Proceso</div>
    </div>
    <div class="stat-card">
        <div class="number" style="color:#5b21b6"><?php echo e($stats['in_route']); ?></div>
        <div class="label">🚛 En Ruta</div>
    </div>
    <div class="stat-card">
        <div class="number" style="color:#166534"><?php echo e($stats['delivered']); ?></div>
        <div class="label">✅ Entregados</div>
    </div>
    <div class="stat-card">
        <div class="number"><?php echo e($stats['total']); ?></div>
        <div class="label">📋 Total activos</div>
    </div>
    <div class="stat-card">
        <div class="number" style="color:#64748b"><?php echo e($stats['archived']); ?></div>
        <div class="label">🗃 Archivados</div>
    </div>
</div>

<div class="card">
    <div class="card-title">
        Pedidos Recientes
        <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-secondary btn-sm" style="float:right">Ver todos →</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Factura</th><th># Cliente</th><th>Cliente</th><th>Fecha</th><th>Estado</th><th></th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><strong><?php echo e($order->invoice_number); ?></strong></td>
                <td><?php echo e($order->customer_number); ?></td>
                <td><?php echo e($order->customer_name); ?></td>
                <td><?php echo e($order->order_date->format('d/m/Y')); ?></td>
                <td><span class="badge badge-<?php echo e($order->status); ?>"><?php echo e($order->status_label); ?></span></td>
                <td><a href="<?php echo e(route('orders.show', $order)); ?>" class="btn btn-secondary btn-sm">Ver</a></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" style="text-align:center;color:#64748b;padding:24px">Sin pedidos registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-top:8px">
    <?php if(auth()->user()->isRole('Ventas')): ?>
    <a href="<?php echo e(route('orders.create')); ?>" class="btn btn-primary" style="text-align:center;padding:14px">➕ Nuevo Pedido</a>
    <?php endif; ?>
    <?php if(auth()->user()->hasAnyRole(['Admin','Ventas'])): ?>
    <a href="<?php echo e(route('orders.archived')); ?>" class="btn btn-secondary" style="text-align:center;padding:14px">🗃 Pedidos Archivados</a>
    <?php endif; ?>
    <?php if(auth()->user()->isAdmin()): ?>
    <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary" style="text-align:center;padding:14px">👥 Gestionar Usuarios</a>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\WebDesing\halcon_final\resources\views/dashboard.blade.php ENDPATH**/ ?>
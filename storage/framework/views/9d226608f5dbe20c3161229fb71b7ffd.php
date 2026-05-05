<?php $__env->startSection('title', 'Pedidos Archivados'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1>Pedidos Archivados</h1>
    <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-secondary">← Regresar a Pedidos</a>
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
        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><strong><?php echo e($order->invoice_number); ?></strong></td>
            <td><?php echo e($order->customer_number); ?></td>
            <td><?php echo e($order->customer_name); ?></td>
            <td><?php echo e($order->order_date->format('d/m/Y')); ?></td>
            <td><span class="badge badge-<?php echo e($order->status); ?>"><?php echo e($order->status_label); ?></span></td>
            <td style="font-size:12px;color:#64748b"><?php echo e($order->updated_at->format('d/m/Y H:i')); ?></td>
            <td style="white-space:nowrap">
                <a href="<?php echo e(route('orders.show', $order)); ?>" class="btn btn-secondary btn-sm">Ver</a>
                <a href="<?php echo e(route('orders.edit', $order)); ?>" class="btn btn-warning btn-sm">Editar</a>
                <form method="POST" action="<?php echo e(route('orders.restore', $order)); ?>" style="display:inline"
                      onsubmit="return confirm('¿Restaurar este pedido?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn btn-success btn-sm">↩ Restaurar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="7" style="text-align:center;color:#64748b;padding:28px">No hay pedidos archivados.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<div style="margin-top:16px">
    <?php echo e($orders->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\WebDesing\halcon_final\resources\views/orders/archived.blade.php ENDPATH**/ ?>
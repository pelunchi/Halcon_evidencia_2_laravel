<?php $__env->startSection('title', 'Pedidos'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1>Pedidos</h1>
    <?php if(auth()->user()->isRole('Ventas')): ?>
        <a href="<?php echo e(route('orders.create')); ?>" class="btn btn-primary">➕ Nuevo Pedido</a>
    <?php endif; ?>
</div>


<form method="GET" action="<?php echo e(route('orders.index')); ?>">
    <div class="filters">
        <div class="form-group">
            <label>Factura</label>
            <input type="text" name="invoice_number" class="form-control"
                   value="<?php echo e(request('invoice_number')); ?>" placeholder="F-0001" style="width:140px"/>
        </div>
        <div class="form-group">
            <label># Cliente</label>
            <input type="text" name="customer_number" class="form-control"
                   value="<?php echo e(request('customer_number')); ?>" placeholder="C-101" style="width:130px"/>
        </div>
        <div class="form-group">
            <label>Estado</label>
            <select name="status" class="form-control" style="width:150px">
                <option value="">Todos</option>
                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>" <?php echo e(request('status')===$key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="form-group">
            <label>Fecha</label>
            <input type="date" name="date" class="form-control" value="<?php echo e(request('date')); ?>" style="width:160px"/>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-secondary" style="margin-left:6px">Limpiar</a>
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
        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
            <td><strong><?php echo e($order->invoice_number); ?></strong></td>
            <td><?php echo e($order->customer_number); ?></td>
            <td><?php echo e($order->customer_name); ?></td>
            <td><?php echo e($order->order_date->format('d/m/Y H:i')); ?></td>
            <td><span class="badge badge-<?php echo e($order->status); ?>"><?php echo e($order->status_label); ?></span></td>
            <td><?php echo e($order->creator->name ?? '—'); ?></td>
            <td style="white-space:nowrap">
                <a href="<?php echo e(route('orders.show', $order)); ?>" class="btn btn-secondary btn-sm">Ver</a>
                <?php if(auth()->user()->hasAnyRole(['Admin','Ventas','Almacen','Ruta'])): ?>
                    <a href="<?php echo e(route('orders.edit', $order)); ?>" class="btn btn-warning btn-sm">Editar</a>
                <?php endif; ?>
                <?php if(auth()->user()->hasAnyRole(['Admin','Ventas'])): ?>
                    <form method="POST" action="<?php echo e(route('orders.destroy', $order)); ?>" style="display:inline"
                          onsubmit="return confirm('¿Archivar este pedido?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger btn-sm">Archivar</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="7" style="text-align:center;color:#64748b;padding:28px">Sin pedidos con esos filtros.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<div style="margin-top:16px">
    <?php echo e($orders->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\WebDesing\halcon_final\resources\views/orders/index.blade.php ENDPATH**/ ?>
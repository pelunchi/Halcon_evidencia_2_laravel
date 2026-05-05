<?php $__env->startSection('title', 'Editar Pedido ' . $order->invoice_number); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1>Editar Pedido — <?php echo e($order->invoice_number); ?></h1>
    <?php if($order->deleted): ?>
        <a href="<?php echo e(route('orders.archived')); ?>" class="btn btn-secondary">← Regresar a Archivados</a>
    <?php else: ?>
        <a href="<?php echo e(route('orders.show', $order)); ?>" class="btn btn-secondary">← Regresar</a>
    <?php endif; ?>
</div>

<?php if($order->deleted): ?>
    <div class="alert alert-warning">
        ⚠️ Este pedido está <strong>archivado</strong>. Puedes editar sus datos, pero no puedes cambiar el estado ni subir fotos hasta que lo restaures.
    </div>
<?php endif; ?>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start">

    
    <?php if(auth()->user()->hasAnyRole(['Ventas','Admin'])): ?>
    <div class="card">
        <div class="card-title">Datos del Pedido</div>
        <form method="POST" action="<?php echo e(route('orders.update', $order)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            <div class="form-row">
                <div class="form-group">
                    <label class="lbl">Número de Factura *</label>
                    <input type="text" name="invoice_number" class="form-control <?php echo e($errors->has('invoice_number') ? 'is-invalid' : ''); ?>"
                           value="<?php echo e(old('invoice_number', $order->invoice_number)); ?>" required/>
                    <?php $__errorArgs = ['invoice_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label class="lbl">Número de Cliente *</label>
                    <input type="text" name="customer_number" class="form-control <?php echo e($errors->has('customer_number') ? 'is-invalid' : ''); ?>"
                           value="<?php echo e(old('customer_number', $order->customer_number)); ?>" required/>
                    <?php $__errorArgs = ['customer_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="lbl">Nombre / Razón Social *</label>
                <input type="text" name="customer_name" class="form-control <?php echo e($errors->has('customer_name') ? 'is-invalid' : ''); ?>"
                       value="<?php echo e(old('customer_name', $order->customer_name)); ?>" required/>
                <?php $__errorArgs = ['customer_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="lbl">Datos Fiscales</label>
                <textarea name="fiscal_data" class="form-control" rows="3"><?php echo e(old('fiscal_data', $order->fiscal_data)); ?></textarea>
            </div>

            <div class="form-group">
                <label class="lbl">Dirección de Entrega *</label>
                <textarea name="delivery_address" class="form-control <?php echo e($errors->has('delivery_address') ? 'is-invalid' : ''); ?>"
                          rows="2" required><?php echo e(old('delivery_address', $order->delivery_address)); ?></textarea>
                <?php $__errorArgs = ['delivery_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="lbl">Notas</label>
                <textarea name="notes" class="form-control" rows="2"><?php echo e(old('notes', $order->notes)); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
    <?php endif; ?>

    
    <div>
        
        <?php if(auth()->user()->isRole('Almacen') && $order->canAdvanceStatus(auth()->user()) && $order->getNextStatus()): ?>
        <div class="card">
            <div class="card-title">Cambiar Estado</div>
            <p style="font-size:13px;color:#64748b;margin-bottom:14px">
                Estado actual: <span class="badge badge-<?php echo e($order->status); ?>"><?php echo e($order->status_label); ?></span>
            </p>
            <form method="POST" action="<?php echo e(route('orders.update', $order)); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <input type="hidden" name="status" value="<?php echo e($order->getNextStatus()); ?>"/>
                <button type="submit" class="btn btn-primary" style="width:100%"
                        onclick="return confirm('¿Cambiar estado a <?php echo e(\App\Models\Order::STATUS_LABELS[$order->getNextStatus()]); ?>?')">
                    ⬆️ Cambiar a <?php echo e(\App\Models\Order::STATUS_LABELS[$order->getNextStatus()]); ?>

                </button>
            </form>
        </div>
        <?php endif; ?>

        
        <?php if(auth()->user()->isRole('Ruta') && $order->status === 'in_route'): ?>
        <div class="card">
            <div class="card-title">Subir Fotografías</div>

            <?php if(!$order->load_photo): ?>
            <div class="form-group">
                <label class="lbl">📦 Foto de Carga de Unidad</label>
                <form method="POST" action="<?php echo e(route('orders.update', $order)); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <input type="file" name="load_photo" accept="image/*" class="form-control" required/>
                    <?php $__errorArgs = ['load_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <button type="submit" class="btn btn-warning" style="margin-top:8px;width:100%">
                        📸 Subir foto de carga
                    </button>
                </form>
            </div>
            <?php else: ?>
            <div class="alert alert-success" style="margin-bottom:14px">✓ Foto de carga ya registrada.</div>
            <?php endif; ?>

            <?php if(!$order->delivery_photo): ?>
            <div class="form-group">
                <label class="lbl">✅ Evidencia de Entrega</label>
                <form method="POST" action="<?php echo e(route('orders.update', $order)); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <input type="file" name="delivery_photo" accept="image/*" class="form-control" required/>
                    <?php $__errorArgs = ['delivery_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <button type="submit" class="btn btn-success" style="margin-top:8px;width:100%"
                            onclick="return confirm('¿Confirmas la entrega? El estado cambiará a Entregado.')">
                        ✅ Subir evidencia y marcar entregado
                    </button>
                </form>
            </div>
            <?php else: ?>
            <div class="alert alert-success">✓ Evidencia de entrega ya registrada.</div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div style="margin-top:8px;display:flex;flex-direction:column;gap:8px">
            <a href="<?php echo e(route('orders.show', $order)); ?>" class="btn btn-secondary" style="width:100%;text-align:center;display:block">
                👁 Ver detalle del pedido
            </a>
            <?php if($order->deleted): ?>
            <form method="POST" action="<?php echo e(route('orders.restore', $order)); ?>"
                  onsubmit="return confirm('¿Restaurar este pedido? Volverá a ser visible en la lista principal.')">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <button type="submit" class="btn btn-success" style="width:100%">↩ Restaurar Pedido</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\WebDesing\halcon_final\resources\views/orders/edit.blade.php ENDPATH**/ ?>
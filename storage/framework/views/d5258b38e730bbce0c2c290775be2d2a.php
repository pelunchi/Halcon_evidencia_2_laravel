<?php $__env->startSection('title', 'Pedido ' . $order->invoice_number); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1>
        <?php echo e($order->invoice_number); ?>

        <span class="badge badge-<?php echo e($order->status); ?>" style="font-size:14px;margin-left:10px"><?php echo e($order->status_label); ?></span>
    </h1>
    <div style="display:flex;gap:8px">
        <?php if(auth()->user()->hasAnyRole(['Admin','Ventas','Almacen','Ruta']) && !$order->deleted): ?>
            <a href="<?php echo e(route('orders.edit', $order)); ?>" class="btn btn-warning">✏️ Editar</a>
        <?php endif; ?>
        <?php if(auth()->user()->hasAnyRole(['Admin','Ventas']) && !$order->deleted): ?>
            <form method="POST" action="<?php echo e(route('orders.destroy', $order)); ?>"
                  onsubmit="return confirm('¿Archivar este pedido?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button class="btn btn-danger">🗃 Archivar</button>
            </form>
        <?php endif; ?>
        <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-secondary">← Regresar</a>
    </div>
</div>

<?php if($order->deleted): ?>
    <div class="alert alert-warning">⚠️ Este pedido está archivado y no es visible en el portal público.</div>
<?php endif; ?>

<div class="detail-grid">
    
    <div>
        <div class="card">
            <div class="card-title">Información del Pedido</div>
            <div class="detail-row"><div class="key">Factura</div><div><?php echo e($order->invoice_number); ?></div></div>
            <div class="detail-row"><div class="key"># Cliente</div><div><?php echo e($order->customer_number); ?></div></div>
            <div class="detail-row"><div class="key">Cliente</div><div><?php echo e($order->customer_name); ?></div></div>
            <div class="detail-row"><div class="key">Fecha</div><div><?php echo e($order->order_date->format('d/m/Y H:i')); ?></div></div>
            <div class="detail-row"><div class="key">Dirección</div><div><?php echo e($order->delivery_address); ?></div></div>
            <div class="detail-row"><div class="key">Datos Fiscales</div><div style="white-space:pre-line"><?php echo e($order->fiscal_data ?: '—'); ?></div></div>
            <div class="detail-row"><div class="key">Notas</div><div><?php echo e($order->notes ?: '—'); ?></div></div>
            <div class="detail-row"><div class="key">Creado por</div><div><?php echo e($order->creator->name ?? '—'); ?></div></div>
        </div>

        
        <div class="card">
            <div class="card-title">Fotografías</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                
                <div>
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#64748b;margin-bottom:8px">
                        📦 Carga de Unidad
                    </div>
                    <?php if($order->load_photo): ?>
                        <img src="<?php echo e(Storage::url($order->load_photo)); ?>" alt="Foto de carga"
                             style="width:100%;border-radius:8px;border:1px solid #e2e8f0"/>
                    <?php else: ?>
                        <div style="height:110px;background:#f8fafc;border:2px dashed #e2e8f0;border-radius:8px;
                                    display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:13px;flex-direction:column;gap:6px">
                            <span style="font-size:24px">📷</span>Sin foto
                        </div>
                        <?php if(auth()->user()->isRole('Ruta') && $order->status === 'in_route' && !$order->deleted): ?>
                            <form method="POST" action="<?php echo e(route('orders.update', $order)); ?>"
                                  enctype="multipart/form-data" style="margin-top:8px">
                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                <input type="file" name="load_photo" accept="image/*" class="form-control"
                                       style="font-size:12px" required/>
                                <button type="submit" class="btn btn-warning btn-sm" style="margin-top:6px;width:100%">
                                    📸 Subir foto de carga
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                
                <div>
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#64748b;margin-bottom:8px">
                        ✅ Evidencia de Entrega
                    </div>
                    <?php if($order->delivery_photo): ?>
                        <img src="<?php echo e(Storage::url($order->delivery_photo)); ?>" alt="Evidencia de entrega"
                             style="width:100%;border-radius:8px;border:1px solid #e2e8f0"/>
                    <?php else: ?>
                        <div style="height:110px;background:#f8fafc;border:2px dashed #e2e8f0;border-radius:8px;
                                    display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:13px;flex-direction:column;gap:6px">
                            <span style="font-size:24px">📷</span>Sin foto
                        </div>
                        <?php if(auth()->user()->isRole('Ruta') && $order->status === 'in_route' && !$order->deleted): ?>
                            <form method="POST" action="<?php echo e(route('orders.update', $order)); ?>"
                                  enctype="multipart/form-data" style="margin-top:8px">
                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                <input type="file" name="delivery_photo" accept="image/*" class="form-control"
                                       style="font-size:12px" required/>
                                <button type="submit" class="btn btn-success btn-sm" style="margin-top:6px;width:100%"
                                        onclick="return confirm('¿Confirmas la entrega? El estado cambiará a Entregado.')">
                                    ✅ Subir evidencia y marcar entregado
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div>
        
        <div class="card">
            <div class="card-title">Estado del Pedido</div>

            
            <?php if(!$order->deleted && $order->canAdvanceStatus(auth()->user()) && $order->getNextStatus()): ?>
                <form method="POST" action="<?php echo e(route('orders.update', $order)); ?>" style="margin-bottom:16px">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="status" value="<?php echo e($order->getNextStatus()); ?>"/>
                    <button type="submit" class="btn btn-primary" style="width:100%"
                            onclick="return confirm('¿Cambiar estado a <?php echo e(\App\Models\Order::STATUS_LABELS[$order->getNextStatus()]); ?>?')">
                        ⬆️ Cambiar a <?php echo e(\App\Models\Order::STATUS_LABELS[$order->getNextStatus()]); ?>

                    </button>
                </form>
            <?php endif; ?>

            
            <ul class="timeline">
                <?php
                    $sequence = \App\Models\Order::STATUS_SEQUENCE;
                    $curIdx   = array_search($order->status, $sequence);
                ?>
                <?php $__currentLoopData = \App\Models\Order::STATUS_LABELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $idx  = array_search($key, $sequence);
                        $done = $idx <= $curIdx;
                        $log  = $order->statusLogs->firstWhere('status', $key);
                    ?>
                    <li>
                        <div class="timeline-dot <?php echo e($done ? 'done' : ''); ?>"><?php echo e($done ? '✓' : ($idx+1)); ?></div>
                        <div class="timeline-body">
                            <strong><?php echo e($label); ?></strong>
                            <?php if($done && $log): ?>
                                <small><?php echo e($log->changed_at->format('d/m/Y H:i')); ?> — <?php echo e($log->user->name); ?></small>
                            <?php else: ?>
                                <small style="color:#94a3b8">Pendiente</small>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>

        
        <?php if($order->statusLogs->count()): ?>
        <div class="card">
            <div class="card-title">Bitácora de Cambios</div>
            <table>
                <thead>
                    <tr><th>Estado</th><th>Usuario</th><th>Fecha</th></tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $order->statusLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><span class="badge badge-<?php echo e($log->status); ?>"><?php echo e($log->status_label); ?></span></td>
                        <td><?php echo e($log->user->name); ?></td>
                        <td><?php echo e($log->changed_at->format('d/m/Y H:i')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\WebDesing\halcon_final\resources\views/orders/show.blade.php ENDPATH**/ ?>
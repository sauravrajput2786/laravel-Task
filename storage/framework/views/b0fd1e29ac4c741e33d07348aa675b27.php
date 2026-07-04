<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="dashboard">
        <div class="card">
            <div class="card__header">
                <h1>Welcome, <?php echo e($user->name); ?></h1>
                <p class="muted">You are signed in to the <strong><?php echo e($client?->client_name); ?></strong> workspace.</p>
            </div>

            <?php if (isset($component)) { $__componentOriginal5194778a3a7b899dcee5619d0610f5cf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5194778a3a7b899dcee5619d0610f5cf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.alert','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5194778a3a7b899dcee5619d0610f5cf)): ?>
<?php $attributes = $__attributesOriginal5194778a3a7b899dcee5619d0610f5cf; ?>
<?php unset($__attributesOriginal5194778a3a7b899dcee5619d0610f5cf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5194778a3a7b899dcee5619d0610f5cf)): ?>
<?php $component = $__componentOriginal5194778a3a7b899dcee5619d0610f5cf; ?>
<?php unset($__componentOriginal5194778a3a7b899dcee5619d0610f5cf); ?>
<?php endif; ?>

            <dl class="detail-list">
                <div class="detail-list__row">
                    <dt>Name</dt>
                    <dd><?php echo e($user->name); ?></dd>
                </div>
                <div class="detail-list__row">
                    <dt>Email</dt>
                    <dd><?php echo e($user->email); ?></dd>
                </div>
                <div class="detail-list__row">
                    <dt>Tenant / Client code</dt>
                    <dd><span class="badge"><?php echo e($client?->client_code); ?></span></dd>
                </div>
                <div class="detail-list__row">
                    <dt>Tenant database</dt>
                    <dd><code><?php echo e(tenant_database_name()); ?></code></dd>
                </div>
                <!-- <div class="detail-list__row">
                    <dt>Session started</dt>
                    <dd><?php echo e(now()->format('d M Y, H:i')); ?></dd>
                </div> -->
                <!-- <?php if($apiToken): ?>
                    <div class="detail-list__row">
                        <dt>API token</dt>
                        <dd>
                            <code class="token-value"><?php echo e($apiToken); ?></code>
                            <p class="muted small">Shown once - copy it now. Use it as a Bearer token on
                                <code>/api/*</code> requests together with header
                                <code>X-Client-Code: <?php echo e($client?->client_code); ?></code>.</p>
                        </dd>
                    </div>
                <?php endif; ?> -->
            </dl>

            <form action="<?php echo e(route('logout')); ?>" method="POST" class="dashboard__logout">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn--danger">Logout</button>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\laravel-Task\resources\views/dashboard.blade.php ENDPATH**/ ?>
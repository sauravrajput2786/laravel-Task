<?php $__env->startSection('title', 'Page not found'); ?>

<?php $__env->startSection('content'); ?>
    <div class="error-page">
        <div class="card error-card">
            <h1 class="error-code">404</h1>
            <p class="error-message"><?php echo e($message ?? "We couldn't find the page you were looking for."); ?></p>
            <a href="<?php echo e(route('login')); ?>" class="btn btn--primary">Back to sign in</a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\Task\tenant-saas\resources\views/errors/404.blade.php ENDPATH**/ ?>
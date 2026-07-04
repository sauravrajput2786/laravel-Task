<?php $__env->startSection('title', 'Something went wrong'); ?>

<?php $__env->startSection('content'); ?>
    <div class="error-page">
        <div class="card error-card">
            <h1 class="error-code">500</h1>
            <p class="error-message">
                Something went wrong on our end. The issue has been logged and we're looking into it.
            </p>
            <a href="<?php echo e(route('login')); ?>" class="btn btn--primary">Back to sign in</a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\Task\tenant-saas\resources\views/errors/500.blade.php ENDPATH**/ ?>
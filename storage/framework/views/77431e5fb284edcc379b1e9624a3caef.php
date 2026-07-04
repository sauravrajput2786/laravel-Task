<?php if($errors->any()): ?>
    <div class="alert alert--error" role="alert">
        <strong>There was a problem with your submission:</strong>
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<?php if(session('status')): ?>
    <div class="alert alert--success" role="alert">
        <?php echo e(session('status')); ?>

    </div>
<?php endif; ?>
<?php /**PATH C:\wamp64\www\Task\tenant-saas\resources\views/components/alert.blade.php ENDPATH**/ ?>
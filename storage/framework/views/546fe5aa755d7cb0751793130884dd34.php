<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', config('app.name')); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
</head>
<body>
    <header class="topbar">
        <div class="topbar__inner">
            <span class="topbar__brand">Laravel Task</span>
            <?php if(auth()->guard()->check()): ?>
                <form action="<?php echo e(route('logout')); ?>" method="POST" class="topbar__logout-form">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn--ghost btn--small">Logout</button>
                </form>
            <?php endif; ?>
        </div>
    </header>

    <main class="page">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="<?php echo e(asset('js/app.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\wamp64\www\laravel-Task\resources\views/layouts/app.blade.php ENDPATH**/ ?>
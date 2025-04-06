
<footer class="footer_sec">
    <div class="container">
    <nav class="navbar navbar-expand-lg">
        <a class="h5 mt-2" href="/">&copy; <?php echo e(opt('site_title')); ?> <?php echo e(date('Y')); ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div>
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="/"><?php echo app('translator')->get( 'navigation.home' ); ?> <span class="sr-only">(current)</span></a>
                </li>
                <?php $__empty_1 = true; $__currentLoopData = $all_pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('page', ['page' => $p])); ?>"><?php echo e($p->page_title); ?></a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('report')); ?>"><?php echo app('translator')->get('v14.report-form-page'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('contact-page')); ?>"><?php echo app('translator')->get('v18.contact-form-page'); ?></a>
                </li>
            </ul>
        </div>
    </nav>
    </div>
</footer><?php /**PATH /Users/crivion/Sites/patrons/resources/views/partials/bottomnavi.blade.php ENDPATH**/ ?>
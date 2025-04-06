<div>
    <div class="p-relative">
        <?php if($search_type === 'mobile'): ?>
            <input class="d-block d-sm-block d-md-none form-control search-topnavi mt-2 mr-sm-2 " type="search" placeholder="<?php echo app('translator')->get('general.searchCreator'); ?>" aria-label="Search" wire:model.debounce.200ms="search">
        <?php elseif($search_type === 'desktop'): ?>
            <input class="d-none d-sm-none d-md-block form-control search-topnavi mt-2 mr-sm-2 " type="search" placeholder="<?php echo app('translator')->get('general.searchCreator'); ?>" aria-label="Search" wire:model.debounce.200ms="search">
        <?php endif; ?>
        <div class="search-spinner" wire:loading>
            <i class="fas fa-spinner fa-spin"></i>
        </div>
    </div>

    <?php if(strlen($search) >= 2): ?>
    <div class="card shadow autocomplete-results">
        <?php if(is_object($creators) AND $creators->count()): ?>
        <div class="row">
            <?php $__currentLoopData = $creators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-12 col-sm-12 col-md-4 text-center">
                <a href="<?php echo e($p->url); ?>">
                    <img src="<?php echo e(secure_image($p->profilePic, 150, 150)); ?>" alt="p pic" class="img-fluid rounded-circle ml-2 mt-1">
                </a>
                <img src="" class="rounded p-1">
            </div>
            <div class="col-12 col-sm-12 col-md-8 text-center text-sm-left">
                <a href="<?php echo e($p->url); ?>" class="text-dark d-block mt-1">
                    <?php echo e($p->name); ?>

                </a>
                <a href="<?php echo e($p->url); ?>">
                    <?php echo e($p->handle); ?>

                </a>
            </div>
            <hr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php /**PATH /Users/crivion/Sites/fansonly/resources/views/livewire/search-creators.blade.php ENDPATH**/ ?>
<div>
    <div class="browseFilters mb-4 text-center">

    <div wire:ignore>
    <select name="category" class="selectpicker show-tick mb-2" wire:model="category">
    <option value="all" <?php if($category == 'all'): ?> selected="selected" <?php endif; ?>><?php echo app('translator')->get('dashboard.allCategories'); ?></option>
    <?php $__empty_1 = true; $__currentLoopData = $all_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <option value="<?php echo e($c->id); ?>" <?php if($category == $c->id): ?> selected="selected" <?php endif; ?>>
            <?php echo e($c->category); ?>

        </option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <option value=""><?php echo app('translator')->get('homepage.noCategories'); ?></option>
    <?php endif; ?>
    </select>
    
    <select name="sortBy" class="selectpicker show-tick mb-2" wire:model="sortBy">
        <option value="popularity" <?php if('sortBy' == 'popularity'): ?> selected="selected" <?php endif; ?>><?php echo app('translator')->get('dashboard.sortByPopularity'); ?></option>
        <option value="postscount" <?php if('sortBy' == 'postscount'): ?> selected="selected" <?php endif; ?>><?php echo app('translator')->get('dashboard.sortByPosts'); ?></option>
        <option value="subscribers" <?php if('sortBy' == 'subscribers'): ?> selected="selected" <?php endif; ?>><?php echo app('translator')->get('dashboard.sortBySubscribers'); ?></option>
        <option value="alphabetically" <?php if('sortBy' == 'alphabetically'): ?> selected="selected" <?php endif; ?>><?php echo app('translator')->get('dashboard.sortByAlphabetically'); ?></option>
        <option value="joindate" <?php if('sortBy' == 'joindate'): ?> selected="selected" <?php endif; ?>><?php echo app('translator')->get('dashboard.sortByJoinDate'); ?></option>
    </select>
    </div>

    </div>

    <?php echo $__env->make('creators.loop', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div wire:loading>
        <i class="fas fa-spinner fa-spin"></i> <?php echo app('translator')->get( 'profile.pleaseWait' ); ?>
    </div>
    
</div>
<?php /**PATH /Users/crivion/Sites/patrons/resources/views/livewire/browse-creators.blade.php ENDPATH**/ ?>
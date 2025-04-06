<div class="row">
    <?php $__currentLoopData = $creators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="<?php if(isset($cols)): ?> col-lg-<?php echo e($cols); ?> <?php else: ?> col-lg-4 <?php endif; ?> col-md-6 col-sm-6">
        <div class="creator_box">
            <div class="creator_img">
                <a href="<?php echo e(route('profile.show', ['username' => $p->username])); ?>">
                    <img src="<?php echo e(secure_image($p->coverPicture, 385, 135)); ?>" class="img-fluid"/>
                </a>
            </div>
            <div class="creator_txt">
                <div class="profile_img">
                    <img src="<?php echo e(secure_image($p->profilePic, 95, 95)); ?>" class="img-fluid"/>
                </div>
                <div class="creator_txtInnr">
                    <h5>
                        <a href="<?php echo e(route('profile.show', ['username' => $p->username])); ?>">
                            <?php echo e($p->name); ?>

                        </a>
                    </h5>
                    <h6>
                        <a href="<?php echo e(route('profile.show', ['username' => $p->username])); ?>">
                            <?php echo e($p->handle); ?>

                        </a>
                    </h6>
                    <ul>
                        <li>
                            <a href="<?php echo e(route('browseCreators', ['category' => $p->category_id, 'category_name' => str_slug($p->category->category) ])); ?>">
                                <i class="fa fa-tags"></i> <?php echo e($p->category->category); ?>

                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('profile.show', ['username' => $p->username])); ?>">
                                <i class="fa fa-align-left mr-1"></i> <?php echo e($p->posts_count); ?> <?php echo app('translator')->get('v192.posts'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="container mt-3">
    <?php if(method_exists($creators, 'links')): ?>
        <?php echo e($creators->links()); ?>

    <?php endif; ?>
</div><?php /**PATH /Users/crivion/Sites/fansonly/resources/views/creators/loop.blade.php ENDPATH**/ ?>
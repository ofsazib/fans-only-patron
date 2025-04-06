<?php $__env->startSection('section_title'); ?>
<strong>Subscriptions Overview</strong>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('section_body'); ?>
	
	<table class="table dataTable">
    <thead>
    <tr>
        <th>ID</th>
        <th>Subscriber</th>
        <th>Creator</th>
        <th>Started</th>
        <th>Expires</th>
        <th>Creator Earnings</th>
        <th>Admin Earnings</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(is_null($t->creator) OR is_null($t->subscriber)): ?>

    <?php else: ?>
        <tr>
            <td><?php echo e($t->id); ?></td>
            <td>
                <?php echo e($t->subscriber->name); ?>

                <br>
                <a href="/<?php echo e($t->subscriber->profile->username); ?>">
                    <?php echo e($t->subscriber->profile->handle); ?>

                </a>
            </td>
            <td>
                <?php echo e($t->creator->name); ?>

                <br>
                <a href="/<?php echo e($t->creator->profile->username); ?>">
                    <?php echo e($t->creator->profile->handle); ?>

                </a>
            </td>
            <td><?php echo e($t->subscription_date->format( 'jS F Y' )); ?></td>
            <td>
                <?php echo e($t->subscription_expires->format( 'jS F Y' )); ?>

                <?php if($t->gateway == 'Admin'): ?>
                <br>
                <small>
                    Created manually by admin, <a href="/admin/subscriptions?delete=<?php echo e($t->id); ?>" class="text-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </small>
                <?php endif; ?>
            </td>
            <td>
                <?php echo e(opt('payment-settings.currency_symbol') . $t->creator_amount); ?>

            </td>
            <td>
                <?php echo e(opt('payment-settings.currency_symbol') . $t->admin_amount); ?>

            </td>
            <td>
                <?php echo e(opt('payment-settings.currency_symbol') . $t->subscription_price); ?>

            </td>
        </tr>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
	</table>
	
<?php $__env->stopSection(); ?>

<?php $__env->startSection('extra_bottom'); ?>
	<?php if(count($errors) > 0): ?>
	    <div class="alert alert-danger">
	        <ul>
	            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	                <li><?php echo e($error); ?></li>
	            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	        </ul>
	    </div>
	<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/admin/subscriptions.blade.php ENDPATH**/ ?>
<?php $__env->startSection('section_title'); ?>
<strong>Transactions Overview</strong>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('section_body'); ?>
	
	<table class="table dataTable">
    <thead>
    <tr>
        <th>ID</th>
        <th>Subscriber</th>
        <th>Creator</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Gateway</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $tx; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(is_null($t->subscription->creator) OR is_null($t->subscription->subscriber)): ?>

    <?php else: ?>
        <tr>
            <td><?php echo e($t->id); ?></td>
            <td>
                <?php echo e($t->subscription->subscriber->name); ?>

                <br>
                <a href="/<?php echo e($t->subscription->subscriber->profile->username); ?>">
                    <?php echo e($t->subscription->subscriber->profile->handle); ?>

                </a>
            </td>
            <td>
                <?php echo e($t->subscription->creator->name); ?>

                <br>
                <a href="/<?php echo e($t->subscription->creator->profile->username); ?>">
                    <?php echo e($t->subscription->creator->profile->handle); ?>

                </a>
            </td>
            <td>
                <?php echo e(opt('payment-settings.currency_symbol') . $t->amount); ?>

            </td>
            <td>
                <?php echo e($t->payment_status); ?>

            </td>
            <td>
                <?php echo e($t->subscription->gateway); ?>

            </td>
            <td><?php echo e($t->created_at->format( 'jS F Y' )); ?></td>
            <td>
                <a href="<?php echo e($t->invoice_url); ?>" target="_blank">View Invoice</a>
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
<?php echo $__env->make('admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/admin/tx.blade.php ENDPATH**/ ?>
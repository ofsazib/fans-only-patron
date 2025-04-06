<?php $__env->startSection('section_title'); ?>
<strong>Tips Overview</strong>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('section_body'); ?>
	
	<table class="table dataTable">
    <thead>
    <tr>
        <th>ID</th>
        <th>Tipper</th>
        <th>Creator</th>
        <th>Date</th>
        <th>Creator Earnings</th>
        <th>Admin Earnings</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $tips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(is_null($t->tipper) OR is_null($t->tipped)): ?>

    <?php else: ?>
        <tr>
            <td><?php echo e($t->id); ?></td>
            <td>
                <?php echo e($t->tipper->name); ?>

                <br>
                <a href="/<?php echo e($t->tipper->profile->username); ?>">
                    <?php echo e($t->tipper->profile->handle); ?>

                </a>
            </td>
            <td>
                <?php echo e($t->tipped->name); ?>

                <br>
                <a href="/<?php echo e($t->tipped->profile->username); ?>">
                    <?php echo e($t->tipped->profile->handle); ?>

                </a>
            </td>
            <td><?php echo e($t->created_at->format( 'jS F Y' )); ?></td>
            <td>
                <?php echo e(opt('payment-settings.currency_symbol') . $t->creator_amount); ?>

            </td>
            <td>
                <?php echo e(opt('payment-settings.currency_symbol') . $t->admin_amount); ?>

            </td>
            <td>
                <?php echo e(opt('payment-settings.currency_symbol') . $t->amount); ?>

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
<?php echo $__env->make('admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/admin/tips.blade.php ENDPATH**/ ?>
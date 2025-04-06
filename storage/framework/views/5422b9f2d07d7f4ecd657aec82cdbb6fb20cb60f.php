<?php $__env->startSection('content'); ?>
<div class="container mt-5">
<div class="col-12 offset-0 col-sm-6 offset-sm-3">
	<div class="card p-3">
        <h3 class="heading"><i class="glyphicon glyphicon-lock"></i> Login</h3>
        
        <?php if( isset( $message ) AND !empty( $message ) ): ?>
        <div class="alert alert-info">
        	<?php echo e($message); ?>

        </div>
        <?php endif; ?>

		<form method="POST" action="<?php echo e(route('adminLogin')); ?>">
		    <?php echo e(csrf_field()); ?>


		    <div>
		        User
		        <input type="text" name="ausername" class="form-control">
		    </div>

		    <div>
		        Password
		        <input type="password" name="apassword" class="form-control">
		    </div>

		    <div>
		    	<br />
		        <button type="submit" class="btn btn-primary">Login</button>
		    </div>
		</form>

		<hr>
		<a class="btn btn-link" href="<?php echo e(url('/password/reset')); ?>">Forgot Your Password?</a>
	</div>

</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('welcome', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/admin-login.blade.php ENDPATH**/ ?>
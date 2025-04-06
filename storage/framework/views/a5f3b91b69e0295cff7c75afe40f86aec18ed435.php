<?php $__env->startSection('seo_title'); ?> <?php echo app('translator')->get('dashboard.accountSettings'); ?> - <?php $__env->stopSection(); ?>

<?php $__env->startSection( 'account_section' ); ?>


<div>
<form method="POST" action="<?php echo e(route( 'saveAccountSettings' )); ?>">
<?php echo csrf_field(); ?>
<div class="shadow-sm card add-padding">

<br/>
<h2 class="ml-2"><i class="fa fa-cog mr-2"></i><?php echo app('translator')->get('dashboard.accountSettings'); ?></h2>
<?php echo app('translator')->get( 'profile.profileSettingsText' ); ?>
<hr>

<div class="row">
	<div class="col-sm-8 col-12">
		<label><strong><?php echo app('translator')->get('dashboard.yourName'); ?></strong></label><br>
		<input type="text" name="name" class="form-control" value="<?php echo e(auth()->user()->name); ?>" required>
	</div>
</div>
<br>

<div class="row">
	<div class="col-sm-8 col-12">
		<label><strong><?php echo app('translator')->get('profile.email'); ?></strong></label><br>
		<input type="email" name="email" class="form-control" value="<?php echo e(auth()->user()->email); ?>" required>
	</div>
</div>
<br>

<div class="row">
	<div class="col-sm-8 col-12">
		<label><strong>New Password</strong> <small><?php echo app('translator')->get('profile.leaveEmpty'); ?></small></label><br>
		<input type="password" name="password" class="form-control">
	</div>
</div>
<br>

<div class="row">
	<div class="col-sm-8 col-12">
		<label><strong>Confirm New Password</strong> <small><?php echo app('translator')->get('profile.leaveEmpty'); ?></small></label><br>
		<input type="password" name="password_confirmation" class="form-control">
	</div>
</div>

</div><!-- /.white-bg -->
<br>

<div class="text-center">
  <input type="submit" name="sbStoreProfile" class="btn btn-lg btn-primary" value="<?php echo app('translator')->get('profile.saveAccountSettings'); ?>">
</div><!-- /.white-bg add-padding -->

</form>
<br/><br/>
</div><!-- /.white-smoke-bg -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make( 'account' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/profile/account-settings.blade.php ENDPATH**/ ?>
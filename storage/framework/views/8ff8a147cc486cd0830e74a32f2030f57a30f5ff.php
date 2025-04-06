<?php $__env->startSection('seo_title'); ?> <?php echo app('translator')->get('dashboard.about'); ?> - <?php $__env->stopSection(); ?>

<?php $__env->startSection( 'section_title' ); ?>
<i class="fa fa-code"></i> <?php echo app('translator')->get( 'dashboard.about' ); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection( 'account_section' ); ?>

<?php echo $__env->yieldContent( 'account_section' ); ?>
<div>

<?php if( isset( $p ) AND $p->isVerified != 'Yes' ): ?>
<div class="alert alert-danger" role="alert">
	<?php if( $p->isVerified == 'No' ): ?>
		<?php echo app('translator')->get( 'dashboard.not-verified' ); ?>
		<br>
		<a href="<?php echo e(route( 'profile.verifyProfile' )); ?>"><?php echo app('translator')->get('dashboard.verify-profile'); ?></a>
	<?php elseif( $p->isVerified = 'Pending' ): ?>
		<?php echo app('translator')->get( 'dashboard.verification-pending' ); ?>
	<?php endif; ?>
</div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route( 'storeMyPage' )); ?>" enctype="multipart/form-data">
<?php echo csrf_field(); ?>
<div class="shadow-sm card add-padding">
<br/>
<h2 class="ml-2"><i class="far fa-edit mr-2"></i><?php echo app('translator')->get('dashboard.myProfile'); ?></h2>
<?php echo app('translator')->get( 'dashboard.aboutText' ); ?>
<hr>
<br/>
<div class="row">
<div class="col-md-3 text-right">
<label><strong><?php echo app('translator')->get('dashboard.yourURL'); ?></strong></label>
</div><!-- /.col-md-3 -->
<div class="col-md-8">
  <div class="input-group mb-3">
  <div class="input-group-prepend">
    <span class="input-group-text" id="basic-addon3"><?php echo e(@env('APP_URL')); ?>/</span>
  </div>
  <input type="text" name="username" class="form-control" placeholder="username" value="<?php if(isset($p)): ?><?php echo e($p->username); ?><?php endif; ?>" aria-describedby="basic-addon3">
</div>
</div><!-- /.col-md-8 -->
</div><!-- /.row -->

<br/>
<div class="row">
<div class="col-md-3 text-right">
<label><strong><?php echo app('translator')->get('dashboard.yourName'); ?></strong></label>
</div><!-- /.col-md-3 -->
<div class="col-md-8">
<input type="text" name="name" value="<?php echo e(auth()->user()->name); ?>" class="form-control">
</div><!-- /.col-md-8 -->
</div><!-- /.row -->
<br/>

<div class="row">
<div class="col-md-3 text-right">
<label><strong><?php echo app('translator')->get('dashboard.headline'); ?></strong></label>
</div>
<div class="col-md-8">
<textarea name="creates" placeholder="<?php echo app('translator')->get('dashboard.exampleOffering'); ?>" class="form-control" rows="7"><?php if(isset($p)): ?><?php echo e($p->creating); ?><?php endif; ?></textarea>
</div>
</div>
<br/>

<div class="row">
<div class="col-md-3 text-right">
<label><strong><?php echo app('translator')->get('dashboard.category'); ?></strong></label>
</div>
<div class="col-md-8">
<select name="category" class="form-control" required="required">
  <option value=""><?php echo app('translator')->get('dashboard.selectCategory'); ?></option>
  <?php $__currentLoopData = $all_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	 <option value="<?php echo e($c->id); ?>" <?php if( $c->id == $userCategory ): ?> selected <?php endif; ?>><?php echo e($c->category); ?></option>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>
</div>
</div>
<br/>

<div class="row">
<div class="col-md-3 text-right">
<label><strong><?php echo app('translator')->get('dashboard.profilePicture'); ?> (<?php echo app('translator')->get('dashboard.minCover'); ?> 200x200)</strong></label>
<br/>
</div>
<div class="col-md-8">
<input type="file" name="profilePic" class="form-control" accept="image/*">
</div>
</div>
<br/>

<div class="row">
<div class="col-md-3 text-right">
<label><strong><?php echo app('translator')->get('dashboard.coverPic'); ?> (<?php echo app('translator')->get('dashboard.minCover'); ?> 960x280)</strong></label>
<br/>
</div>
<div class="col-md-8">
<input type="file" name="coverPic" class="form-control" accept="image/*">
</div>
</div>

<br/>
</div><!-- /.white-bg -->

<br/>
<div class="shadow-sm card add-padding">
<br/>
<h4><?php echo app('translator')->get('dashboard.socialProfiles'); ?></h4>
<label><strong>Facebook</strong></label>
<input type="text" name="fbUrl" placeholder="https://facebook.com" class="form-control" value="<?php if(isset($p)): ?><?php echo e($p->fbUrl); ?><?php endif; ?>">
<label><strong>Instagram</strong></label>
<input type="text" name="instaUrl" placeholder="https://instagram.com" class="form-control" value="<?php if(isset($p)): ?><?php echo e($p->instaUrl); ?><?php endif; ?>">
<label><strong>Twitter</strong></label>
<input type="text" name="twUrl" placeholder="https://twitter.com" class="form-control" value="<?php if(isset($p)): ?><?php echo e($p->twUrl); ?><?php endif; ?>">
<label><strong>Youtube</strong></label>
<input type="text" name="ytUrl" placeholder="https://youtube.com" class="form-control" value="<?php if(isset($p)): ?><?php echo e($p->ytUrl); ?><?php endif; ?>">
<label><strong>Twitch</strong></label>
<input type="text" name="twitchUrl" placeholder="https://twitch.tv" class="form-control" value="<?php if(isset($p)): ?><?php echo e($p->twitchUrl); ?><?php endif; ?>">
</div>
<br/>

<div class="text-center">
  <input type="submit" name="sbStoreProfile" class="btn btn-lg btn-primary" value="<?php echo app('translator')->get('dashboard.saveProfile'); ?>">
</div><!-- /.white-bg add-padding -->

</form>
<br/><br/>
</div><!-- /.white-smoke-bg -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make( 'account' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/profile/create.blade.php ENDPATH**/ ?>
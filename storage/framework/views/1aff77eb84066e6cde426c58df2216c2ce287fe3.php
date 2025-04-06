<div class="card shadow-sm p-4">
	<h4><?php echo app('translator')->get( 'dashboard.headline' ); ?></h4>
	
	<?php echo nl2br(e($profile->creating)); ?>

	<br><br>

	<h4><?php echo app('translator')->get( 'profile.category' ); ?></h4>
	<a href="<?php echo e(route('browseCreators', ['category' => $profile->category->id, 'category_name' => $profile->category->category])); ?>">
		<i class="fas fa-tags"></i> <?php echo e($profile->category->category); ?>

	</a>
	<br>

	<?php if( $profile->hasSocialProfiles() ): ?>
	
		<h4><?php echo app('translator')->get( 'profile.socialProfiles' ); ?></h4>

		<h5>
		<?php if( !is_null( $profile->fbUrl ) && !empty( $profile->fbUrl ) ): ?>
		<a href="<?php echo e($profile->fbUrl); ?>" target="_blank" rel="nofollow" class="noHover">
			<i class="fab fa-facebook-f mr-2"></i>
		</a> 
		<?php endif; ?>

		<?php if( !is_null( $profile->instaUrl ) && !empty( $profile->instaUrl ) ): ?>
		<a href="<?php echo e($profile->instaUrl); ?>" target="_blank" rel="nofollow" class="noHover">
			<i class="fab fa-instagram mr-2 text-danger"></i>
		</a> 
		<?php endif; ?>

		<?php if( !is_null( $profile->twUrl ) && !empty( $profile->twUrl ) ): ?>
		<a href="<?php echo e($profile->twUrl); ?>" target="_blank" rel="nofollow" class="noHover text-info">
			<i class="fab fa-twitter mr-2"></i>
		</a> 
		<?php endif; ?>

		<?php if( !is_null( $profile->ytUrl ) && !empty( $profile->ytUrl ) ): ?>
		<a href="<?php echo e($profile->ytUrl); ?>" target="_blank" rel="nofollow" class="noHover text-danger">
			<i class="fab fa-youtube mr-2"></i>
		</a> 
		<?php endif; ?>

		<?php if( !is_null( $profile->twitchUrl ) && !empty( $profile->twitchUrl ) ): ?>
		<a href="<?php echo e($profile->twitchUrl); ?>" target="_blank" rel="nofollow" class="noHover">
			<i class="fab fa-twitch"></i>
		</a> 
		<?php endif; ?>
		</h5>

	<?php endif; ?>

</div><?php /**PATH /Users/crivion/Sites/patrons/resources/views/profile/sidebar.blade.php ENDPATH**/ ?>
<?php $__env->startSection('seo_title'); ?> <?php echo e($profile->handle); ?> - <?php $__env->stopSection(); ?>

<?php $__env->startSection( 'content' ); ?>
<div class="coverPic"></div>

<div class="white-smoke-bg white-smoke-bg-profile-page">
<div class="white-bg add-padding shadow-sm">
<div class="container">

<div class="row">
<div class="col-12 col-sm-4 col-md-3 col-lg-2 mb-5 mb-sm-0">
<div class="profilePic <?php if($profile->user->isOnline()): ?> profilePicOnline <?php else: ?> profilePicOffline <?php endif; ?> shadow">
	<a href="<?php echo e($profile->url); ?>">
		<img src="<?php echo e(secure_image($profile->profilePic, 150, 150)); ?>" alt="profile pic" class="img-fluid">
	</a>
</div>
</div>
<div class="col-12 col-sm-8 col-md-9 col-lg-10 text-center text-sm-left">
	<div class="row">
		<div class="col-12 col-sm-6">
			<h4 class="profile-name">
				<a href="<?php echo e($profile->url); ?>">
					<?php echo e($profile->name); ?>

				</a>
			</h4>
			<a href="<?php echo e($profile->url); ?>">
				<?php echo e($profile->handle); ?> <?php if($profile->isVerified == 'Yes'): ?> <i class="fas fa-check-circle text-primary"></i> <?php endif; ?>  
			</a>
			<br><br>

			<i class="far fa-grin-stars mr-1"></i> <?php echo e($profile->fans_count); ?> <?php echo app('translator')->get('general.paid-fans'); ?>
			<br>
			
			<i class="fas fa-users"></i> <?php echo e($profile->followers->count()); ?> <?php echo app('translator')->get('general.free-subscribers'); ?>
			<br>

			<i class="fas fa-align-left" data-toggle="tooltip" title="Total Posts"></i> <?php echo e($profile->posts->count()); ?> &nbsp;
			<i class="fas fa-image" data-toggle="tooltip" title="Images"></i> <?php echo e($profile->posts->where('media_type', 'Image')->count()); ?> &nbsp;
			<i class="fas fa-music" data-toggle="tooltip" title="Audios"></i> <?php echo e($profile->posts->where('media_type', 'Audio')->count()); ?> &nbsp;
			<i class="fas fa-video" data-toggle="tooltip" title="Videos"></i> <?php echo e($profile->posts->where('media_type', 'Video')->count()); ?> 
		</div>

		<div class="col-12 col-sm-6">

			<h4 class="profile-name mb-3">
				<?php echo app('translator')->get( 'profile.follow' ); ?>
			</h4>

			<?php if($profile->monthlyFee): ?>

				<?php if(auth()->check() && auth()->user()->hasSubscriptionTo($profile->user)): ?>
					<a href="<?php echo e(route('mySubscriptions')); ?>" class="btn btn-primary btn-sm mb-2"><i class="fas fa-eye"></i> <?php echo app('translator')->get('general.viewSubscription'); ?></a>
				<?php else: ?>
					<div class="dropdown show z-9999">
					<a href="javascript:void(0)" class="btn btn-primary btn-sm mb-2 dropdown-toggle" id="premiumPostsLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-unlock mr-1"></i> <?php echo app('translator')->get( 'profile.unlock' ); ?> - <?php echo e(opt('payment-settings.currency_symbol') . number_format($profile->monthlyFee,2)); ?>

					</a>
					<div class="dropdown-menu" aria-labelledBy="premiumPostsLink">

						
						<?php if(opt('card_gateway', 'Stripe') == 'Stripe'): ?>
							<?php if(auth()->check() && opt('stripeEnable', 'No') == 'Yes' && auth()->user()->paymentMethods()->count()): ?>
								<a class="dropdown-item" href="<?php echo e(route('subscribeCreditCard', [ 'user' => $profile->user->id ])); ?>">
									<?php echo app('translator')->get('general.creditCard'); ?>
								</a>
							<?php elseif(auth()->check() && !auth()->user()->paymentMethods()->count() && opt('stripeEnable', 'No') == 'Yes'): ?>
								<a class="dropdown-item" href="<?php echo e(route('billing.cards')); ?>">
									<?php echo app('translator')->get('general.addNewCard'); ?>
								</a>
							<?php elseif(opt('stripeEnable', 'No') == 'Yes'): ?>
								<a class="dropdown-item" href="<?php echo e(route('login')); ?>">
									<?php echo app('translator')->get('general.creditCard'); ?>
								</a>
							<?php endif; ?>
						<?php endif; ?>

						
						<?php if(opt('card_gateway', 'Stripe') == 'CCBill'): ?>
							<a class="dropdown-item" href="<?php echo e(route('subscribeCCBill', [ 'user' => $profile->user->id ])); ?>">
								<?php echo app('translator')->get('general.creditCard'); ?>
							</a>
						<?php endif; ?>

						
						<?php if(opt('card_gateway', 'Stripe') == 'PayStack'): ?>
							<?php if(auth()->check() && auth()->user()->paymentMethods()->count()): ?>
								<a class="dropdown-item" href="<?php echo e(route('subscribePayStack', [ 'user' => $profile->user->id ])); ?>">
									<?php echo app('translator')->get('general.creditCard'); ?>
								</a>
							<?php elseif(auth()->check() && !auth()->user()->paymentMethods()->count()): ?>
								<a class="dropdown-item" href="<?php echo e(route('billing.cards')); ?>">
									<?php echo app('translator')->get('general.addNewCard'); ?>
								</a>
							<?php else: ?>
								<a class="dropdown-item" href="<?php echo e(route('login')); ?>">
									<?php echo app('translator')->get('general.creditCard'); ?>
								</a>
							<?php endif; ?>
						<?php endif; ?>

						
						<?php if(opt('card_gateway', 'Stripe') == 'MercadoPago'): ?>
							<?php if(auth()->check()): ?>
								<a class="dropdown-item" href="<?php echo e(route('subscribeMercadoPago', [ 'user' => $profile->user->id ])); ?>">
									<?php echo app('translator')->get('general.creditCard'); ?>
								</a>
							<?php else: ?>
								<a class="dropdown-item" href="<?php echo e(route('login')); ?>">
									<?php echo app('translator')->get('general.creditCard'); ?>
								</a>
							<?php endif; ?>
						<?php endif; ?>

						
						<?php if(opt('card_gateway', 'Stripe') == 'TransBank'): ?>
							<a class="dropdown-item" href="<?php echo e(route('subscribeWithWBPlus', [ 'user' => $profile->user->id ])); ?>">
								<?php echo app('translator')->get('general.creditCard'); ?>
							</a>
						<?php endif; ?>

						
						<?php if(opt('paypalEnable', 'No') == 'Yes'): ?>
							<a class="dropdown-item" href="<?php echo e(route('subscribeViaPaypal', [ 'user' => $profile->user->id ])); ?>">
								<?php echo app('translator')->get('general.PayPal'); ?>
							</a>
						<?php endif; ?>


						
						<?php if(opt('card_gateway', 'Stripe') == 'Crypto'): ?>
							<a class="dropdown-item" href="<?php echo e(route('subscribeCoinPayments', [ 'user' => $profile->user->id ])); ?>">
								<?php echo app('translator')->get('v192.crypto'); ?>
							</a>
						<?php endif; ?>
					</div>
					</div>
				<?php endif; ?>
			<?php endif; ?> 

			<?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('followbutton', [ 'profileId' => $profile->id ])->dom;
} elseif ($_instance->childHasBeenRendered('JFL9sDK')) {
    $componentId = $_instance->getRenderedChildComponentId('JFL9sDK');
    $componentTag = $_instance->getRenderedChildComponentTagName('JFL9sDK');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('JFL9sDK');
} else {
    $response = \Livewire\Livewire::mount('followbutton', [ 'profileId' => $profile->id ]);
    $dom = $response->dom;
    $_instance->logRenderedChild('JFL9sDK', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>
			
		</div>
	</div>
	<br>
</div>
</div>
</div>
</div>
<br>
<div class="container no-padding">
<div class="row">

<div class="col-12 d-block d-sm-block d-md-none mb-4">
<?php echo $__env->make( 'profile.sidebar' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>

<div class="col-12 col-md-7">

	<?php if( auth()->check() AND auth()->user()->profile->id == $profile->id ): ?>
		<?php echo $__env->make('posts.create-post', [ 'user' => auth()->user() ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	<?php endif; ?>

	<div class="postsList">
		<?php echo $__env->make( 'posts.feed', [ 'profile' => $profile, 'pinned' => $pinned ] , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	</div>

	<div class="text-center loadingPostsMsg d-none">
	  <h3 class="text-secondary"><i class="fas fa-spinner fa-spin mr-2"></i> <?php echo app('translator')->get( 'post.isLoading' ); ?></h3>
	</div>

	<div class="text-center noMorePostsMsg d-none mb-5">
		<div class="card shadow p-3">
			<h3 class="text-secondary"><?php echo app('translator')->get( 'post.noMorePosts' ); ?></h3>
		</div>
	</div>

</div><!-- col-sm-8 col-12 -->

<div class="col-12 col-md-4 d-none d-sm-none d-md-block d-lg-block">
	<div class="">

	<?php if( $feed->count() ): ?>
		<input class="lastId d-none" value="<?php echo e($feed->last()->id); ?>" type="number"/>
	<?php endif; ?>

	<?php echo $__env->make( 'profile.sidebar' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	</div>
	<br>
</div>

</div>	<!-- . posts -->

</div>
</div>

<br><br><br>
</div><!-- ./white-smoke bg-->

<?php $__env->stopSection(); ?>

<?php if($profile->coverPic && !empty( $profile->coverPic )): ?>
<?php $__env->startPush( 'extraCSS' ); ?>
<style>
.coverPic {
	background-image: url('<?php echo e(asset('public/uploads/' . $profile->coverPicture)); ?>');
}
</style>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<?php $__env->startPush( 'extraJS' ); ?>
<script>
	$( function() {

		<?php if( auth()->check() AND auth()->user()->profile->id == $profile->id ): ?>
		// auto expand textarea
		document.getElementById('createPost').addEventListener('keyup', function() {
		    this.style.overflow = 'hidden';
		    this.style.height = 0;
		    this.style.height = this.scrollHeight + 'px';
		}, false);
		<?php endif; ?>
		
		$(document.body).on('touchmove', onScroll); // for mobile
		$(window).on('scroll', onScroll);

		function onScroll() {

			if($(window).scrollTop() + $(window).height() >= $(document).height()) {

				// show spinner
				$( '.loadingPostsMsg' ).removeClass( 'd-none' );

				var lastId = $( '.lastId' ).val();

				$.getJSON( '<?php echo e(route( 'loadPostsForProfile', [ 'profile' => $profile->id, 'lastId' => '/' ])); ?>/' + lastId, function( resp ) {

					if( resp.lastId != 0 ) {

						// append html
						$( '.postsList' ).append( resp.view );
						$('.lastId').val(resp.lastId);

					}else{

						// cancel scroll event
						$(window).off('scroll');

						$( '.noMorePostsMsg' ).removeClass( 'd-none' );
					}

					$( '.loadingPostsMsg' ).addClass( 'd-none' );

					window.livewire.rescan();

				});
			}
		}		

	});
</script>
<?php $__env->stopPush(); ?>


<?php $__env->startPush('extraCSS'); ?>
<style>
@media  only screen and (min-width: 768px) and (max-height: 1024px) {
    #wrap {
        margin-top: -10px !important;
    }
}
@media  only screen and (min-height: 768px) and (max-width: 1024px) {
    #wrap {
        margin-top: -10px !important;
    }
}
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make( 'welcome' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/fansonly/resources/views/profile/user-profile.blade.php ENDPATH**/ ?>
<?php $__env->startSection('seo_title'); ?> <?php echo app('translator')->get('navigation.feed'); ?> - <?php $__env->stopSection(); ?>

<?php $__env->startSection( 'content' ); ?>
<div class="white-smoke-bg pt-4 pb-3">
<div class="container add-padding">
<div class="row">

<?php echo $__env->make('posts.sidebar-mobile', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="col-12 col-md-7">

<?php if( auth()->check() ): ?>
	<?php echo $__env->make('posts.create-post', [ 'user' => auth()->user(), 'profile' => auth()->user()->profile ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<?php if( $feed->count() ): ?>
	
<div class="postsList">
	<?php $__currentLoopData = $feed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<?php $__env->startComponent( 'posts.single', [ 'post' => $post ] ); ?> <?php echo $__env->renderComponent(); ?>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="text-center loadingPostsMsg d-none">
  <h3 class="text-secondary"><i class="fas fa-spinner fa-spin mr-2"></i> <?php echo app('translator')->get( 'post.isLoading' ); ?></h3>
</div>

<div class="text-center noMorePostsMsg d-none">
	<div class="card p-3">
		<h3 class="text-secondary"><?php echo app('translator')->get( 'post.noMorePosts' ); ?></h3>
	</div>
</div>

<?php else: ?>

	<div class="card p-4 mb-4 text-center">
		<h5 class="text-secondary">
			<i class="fas fa-comment-slash"></i> <?php echo app('translator')->get( 'post.noSubscriptions' ); ?>
		</h5>
	</div>

<?php endif; ?>
</div>

<?php echo $__env->make('posts.sidebar-desktop', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</div><!-- paddin top 5-->
</div><!-- ./container -->
</div><!-- .swhite-smoke -->
<?php $__env->stopSection(); ?>


<?php $__env->startPush( 'extraJS' ); ?>
<script>
	$( function() {

		<?php if( auth()->check() ): ?>
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

				$.getJSON( '<?php echo e(route( 'loadMorePosts', [ 'lastId' => '/' ])); ?>/' + lastId, function( resp ) {

					if( resp.lastId != 0 ) {

						// append html
						$( '.postsList' ).append( resp.view );
						$('.lastId').val(resp.lastId);

					}else{

						// cancel scroll event
						$(window).off('scroll');

						$( '.noMorePostsMsg' ).removeClass( 'd-none' );

						$('.lastId').val(0);
					}

					$( '.loadingPostsMsg' ).addClass( 'd-none' );

					window.livewire.rescan();

				});
			}
		}
	});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make( 'welcome' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/user-feed.blade.php ENDPATH**/ ?>
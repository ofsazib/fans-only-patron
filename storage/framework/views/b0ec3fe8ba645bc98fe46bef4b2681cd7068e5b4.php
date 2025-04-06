<?php $__env->startSection('seo_title'); ?> #<?php echo e($post->id); ?> - <?php $__env->stopSection(); ?>

<?php $__env->startSection( 'content' ); ?>
<div class="white-smoke-bg pt-4 pb-3">
<div class="container add-padding">
<div class="row">

<?php echo $__env->make('posts.sidebar-mobile', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="col-12 col-md-7">

<div class="postsList">
	<?php $__env->startComponent( 'posts.single', [ 'post' => $post ] ); ?> <?php echo $__env->renderComponent(); ?>
</div>

</div>

<?php echo $__env->make('posts.sidebar-desktop', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</div><!-- paddin top 5-->
</div><!-- ./container -->
</div><!-- .swhite-smoke -->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('extraJS'); ?>
<?php if(auth()->check()): ?>
<script>
$(function(){
	$('.loadComments').trigger('click');
});
</script>
<?php endif; ?>
<?php $__env->stopPush(); ?>
<?php echo $__env->make( 'welcome' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/posts/one.blade.php ENDPATH**/ ?>
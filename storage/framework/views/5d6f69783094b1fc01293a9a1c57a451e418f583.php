<?php $__env->startSection( 'content' ); ?>
<div class="white-smoke-bg">
<br/>

<div class="container">
<div class="row">

<div class="col-md-4 d-block d-sm-none mb-3">
<a class="btn btn-dark mt-4" data-toggle="collapse" href="#mobileAccountNavi" role="button" aria-expanded="false" aria-controls="collapseExample">
    <i class="fas fa-list mr-1"></i> <?php echo app('translator')->get('navigation.accountNavigation'); ?>
  </a>
<div class="collapse mt-2" id="mobileAccountNavi">
    <?php echo $__env->make( 'partials/dashboardnavi' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
</div><!-- /.col-md-3 -->

<div class="col-md-8">
<?php echo $__env->yieldContent( 'account_section' ); ?>
</div><!-- /.col-md-8 -->

<div class="col-md-4 d-none d-sm-block">
<?php echo $__env->make( 'partials/dashboardnavi' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div><!-- /.col-md-3 -->

</div><!-- ./row ( main ) -->
</div><!-- /.container -->
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make( 'welcome' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/account.blade.php ENDPATH**/ ?>
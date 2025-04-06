<?php $__env->startSection('section_title'); ?>
<strong>Configure Homepage Earnings Simulator</strong>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('section_body'); ?>


<form method="POST" action="/admin/simulator-store-config">
<?php echo e(csrf_field()); ?>


<div class="row">
    <div class="col-xs-12 col-md-3">

        <strong>Audience Slider</strong>
        <hr>
        <strong>Min Value</strong><br>
        <input type="number" name="SL_AUDIENCE_MIN" value="<?php echo e(opt('SL_AUDIENCE_MIN', 10)); ?>"/>
        <br><br>

        <strong>Max Value</strong><br>
        <input type="number" name="SL_AUDIENCE_MAX" value="<?php echo e(opt('SL_AUDIENCE_MAX', 1000)); ?>"/>
        <br><br>

        <strong>Predefined Value</strong><br>
        <input type="number" name="SL_AUDIENCE_PRE_NUM" value="<?php echo e(opt('SL_AUDIENCE_PRE_NUM', 100)); ?>"/>
        <br><br>

    </div>

    <div class="col-xs-12 col-md-3">
        <strong>Membership Slider</strong>
        <hr>

        <strong>Min Amount</strong><br>
        <input type="number" name="MSL_MEMBERSHIP_FEE_MIN" value="<?php echo e(opt('MSL_MEMBERSHIP_FEE_MIN', 9)); ?>"/>
        <br><br>

        <strong>Max Amount</strong><br>
        <input type="number" name="MSL_MEMBERSHIP_FEE_MAX" value="<?php echo e(opt('MSL_MEMBERSHIP_FEE_MAX', 900)); ?>"/>
        <br><br>

        <strong>Predefined Amount</strong><br>
        <input type="number" name="MSL_MEMBERSHIP_FEE_PRESET" value="<?php echo e(opt('MSL_MEMBERSHIP_FEE_PRESET', 9)); ?>"/>
        <br><br>

    <hr>
    <input type="submit" name="sb" value="Save Simulator Settings"  class="btn btn-primary"/>

    </div><!-- col -->
</div><!-- ./row -->
    
    
</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/fanspatrons/public/resources/views/admin/simulator-config.blade.php ENDPATH**/ ?>
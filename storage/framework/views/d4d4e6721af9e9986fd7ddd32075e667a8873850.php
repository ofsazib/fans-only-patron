<?php $__env->startSection('seo_title'); ?> <?php echo app('translator')->get('navigation.messages'); ?> - <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="white-smoke-bg pt-4 pb-3">
<div class="container no-padding">

    
    

    
    
    <div id="vue-messages-app"></div>

</div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('extraJS'); ?>
<script src="<?php echo e(asset('resources/vueapp/dist/vuejs-bundle-v2.1.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('welcome', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/messages/inbox.blade.php ENDPATH**/ ?>
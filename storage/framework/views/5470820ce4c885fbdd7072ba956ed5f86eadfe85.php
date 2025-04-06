<div>
    <a href="<?php echo e(route('notifications.index')); ?>">
        <?php echo app('translator')->get('navigation.myNotifications'); ?> 
        <span class="notifc"><?php echo e(auth()->user()->unreadNotifications()->count()); ?></span>
    </a>
</div>
<?php /**PATH /Users/crivion/Sites/patrons/resources/views/livewire/notifications-icon.blade.php ENDPATH**/ ?>
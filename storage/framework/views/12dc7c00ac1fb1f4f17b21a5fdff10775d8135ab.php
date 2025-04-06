<div class="col-12 col-md-4 d-none d-sm-none d-md-block d-lg-block" style="margin-top:-30px">
	<?php if( isset($feed) && $feed->count() ): ?>
		<input type="number" class="lastId d-none" value="<?php echo e($feed->last()->id); ?>">
	<?php endif; ?>

	<?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('creators-sidebar')->dom;
} elseif ($_instance->childHasBeenRendered('7sN2PyR')) {
    $componentId = $_instance->getRenderedChildComponentId('7sN2PyR');
    $componentTag = $_instance->getRenderedChildComponentTagName('7sN2PyR');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('7sN2PyR');
} else {
    $response = \Livewire\Livewire::mount('creators-sidebar');
    $dom = $response->dom;
    $_instance->logRenderedChild('7sN2PyR', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>

	<br>
</div><?php /**PATH /var/www/works.crivion.com/resources/views/posts/sidebar-desktop.blade.php ENDPATH**/ ?>
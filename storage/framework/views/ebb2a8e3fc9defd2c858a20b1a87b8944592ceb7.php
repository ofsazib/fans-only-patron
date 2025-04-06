<div class="col-12 col-md-4 d-none d-sm-none d-md-block d-lg-block" style="margin-top:-30px">
	<?php if( isset($feed) && $feed->count() ): ?>
		<input type="number" class="lastId d-none" value="<?php echo e($feed->last()->id); ?>">
	<?php endif; ?>

	<?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('creators-sidebar')->dom;
} elseif ($_instance->childHasBeenRendered('vdJLhpj')) {
    $componentId = $_instance->getRenderedChildComponentId('vdJLhpj');
    $componentTag = $_instance->getRenderedChildComponentTagName('vdJLhpj');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('vdJLhpj');
} else {
    $response = \Livewire\Livewire::mount('creators-sidebar');
    $dom = $response->dom;
    $_instance->logRenderedChild('vdJLhpj', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>

	<br>
</div><?php /**PATH /Users/crivion/Sites/fansonly/resources/views/posts/sidebar-desktop.blade.php ENDPATH**/ ?>
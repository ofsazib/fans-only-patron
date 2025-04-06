<div class="col-12 col-md-4 d-none d-sm-none d-md-block d-lg-block" style="margin-top:-30px">
	@if( isset($feed) && $feed->count() )
		<input type="number" class="lastId d-none" value="{{ $feed->last()->id }}">
	@endif

	@livewire('creators-sidebar')

	<br>
</div>
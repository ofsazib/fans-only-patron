@extends( 'account' )

@section('seo_title') @lang('navigation.my-subscribers') - @endsection

@section( 'account_section' )

<div>


<div class="shadow-sm card add-padding">
	<h2 class="mt-3 ml-2 mb-4">
		<i class="fas fa-user-lock mr-1"></i> @lang('navigation.my-subscribers')
	</h2>

	@livewire( 'user-subscribers-list', [ 'subscribers' => $subscribers ] )

	<br>

</div>

<br/><br/>
</div><!-- /.white-smoke-bg -->
@endsection
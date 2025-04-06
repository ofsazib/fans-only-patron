@extends( 'account' )

@section('seo_title') @lang('navigation.my-subscriptions') - @endsection

@section( 'account_section' )

<div>


<div class="shadow-sm card add-padding">
	<h2 class="mt-3 ml-2 mb-4">
		<i class="fas fa-user-edit mr-1"></i> @lang('navigation.my-subscriptions')
	</h2>

	@livewire( 'user-subscriptions-list' )
	
</div>

<br/><br/>
</div><!-- /.white-smoke-bg -->
@endsection
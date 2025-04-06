@extends( 'account' )

@section('seo_title') @lang('dashboard.profileVerified') - @endsection

@section( 'account_section' )

<div>


<div class="shadow-sm card add-padding">
	<h2>
		<h2 class="ml-2"><i class="fas fa-user-check mr-2"></i>@lang('dashboard.verify-profile')</h2>
	</h2>
	<hr>

	<div class="alert alert-success">
		<i class="fas fa-check-circle"></i> @lang( 'dashboard.profileVerified' )
	</div>

	<p class="text-center">
		
		@lang( 'dashboard.startSettingPayments' )
		<br><br>

		<a href="{{ route( 'profile.setFee' )}}" class="btn btn-primary btn-sm">
			@lang('dashboard.creatorSetup')
		</a>

	</p>

	<br>

</div>

<br/><br/>
</div><!-- /.white-smoke-bg -->
@endsection
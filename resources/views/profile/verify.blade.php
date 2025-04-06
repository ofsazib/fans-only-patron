@extends( 'account' )

@section('seo_title') @lang('dashboard.verify-profile') - @endsection

@section( 'account_section' )

<div>
<form method="POST" action="{{ route( 'processVerification' ) }}" enctype="multipart/form-data">
@csrf
<div class="shadow-sm card add-padding">
<br/>
<h2 class="ml-2"><i class="fas fa-user-check mr-2"></i>@lang('dashboard.verify-profile')</h2>
@lang( 'dashboard.verification-text' )
<hr>

	@if( isset( $p ) AND $p->isVerified == 'No' )
	<div class="alert alert-warning" role="alert">
		@lang( 'dashboard.send-for-verification' )
	</div>
	@endif

	@if( isset( $p ) AND $p->isVerified == 'Yes' )
	<div class='alert alert-success'>
		<h4><i class="fas fa-check-circle"></i> @lang( 'dashboard.successfully-verified' ) </h4>
	</div>
	@endif

	@if( isset( $p ) AND $p->isVerified == 'Pending' )
	<div class='alert alert-info'>
		<h4><i class="fas fa-check-circle"></i> @lang( 'dashboard.pending-verification' ) </h4>
	</div>
	@else

	<label><strong>@lang('dashboard.yourCountry')</strong></label>
	<select name="country" class="form-control" required>
	<option value="">@lang('profile.selectCountry')</option>
	@foreach( $countries as $country )
	<option value="{{ $country }}">{{ $country }}</option>
	@endforeach
	</select>
	<br>


	<label><strong>@lang('dashboard.yourCity')</strong></label>
	<input type="text" class="form-control" name="city" value="{{ $p->city ?? old( 'city' ) }}" required>
	<br>


	<label><strong>@lang('dashboard.yourFullAddress')</strong></label>
	<textarea class="form-control" rows="5" name="address" required>{{ $p->address ?? old( 'address' ) }}</textarea>
	<br>

	<label><strong>@lang('dashboard.idUpload')</strong></label>
    <input type="file" name="idUpload" accept="image/*" required>

    <br>

<div class="text-center">
  <br>
  <input type="submit" name="sbStoreProfile" class="btn btn-lg btn-primary" value="@lang('dashboard.sendForApproval')">
</div><!-- /.white-bg add-padding -->

</form>

@endif

</div><!-- /.white-bg -->

<br/><br/>
</div><!-- /.white-smoke-bg -->
@endsection
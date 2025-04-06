@extends('welcome')

@section('seo_title') PayPal - @endsection

@section('content')
<div class="container mt-5">
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 offset-0 offset-sm-0 offset-md-3">
		<div class="card shadow-sm">

			<div class="alert alert-secondary text-center">
			<h5>
				@lang('general.subscribeInfo', [
					'user' => '<a href="'.route('profile.show', [
						'username' => $user->profile->username]).'">'.$user->profile->handle.'</a>',
						'amount'   => opt('payment-settings.currency_symbol') . number_format($user->profile->finalPrice,2)
				])
			</h5>
			</div>


			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="paypalform" id="paypalform">
			<input type="hidden" name="business" value="{{ opt('paypal_email', 'email@paypal.com') }}"/>
			<input type="hidden" name="return" value="{{ route('profile.show', ['username' => $user->profile->username]) }}" />
			<input type="hidden" name="cancel_return" value="{{ route('profile.show', ['username' => $user->profile->username]) }}"/>
			<input type="hidden" name="notify_url" value="{{ route('paypalProcessing', ['creator' => $user->id, 'subscriber' => auth()->id()]) }}"/>
			<input type="hidden" name="item_name" value="Subscription to {{ $user->profile->username }}"/>
			<input type="hidden" name="item_number" value="Monthly Subscription"/>	
			<input type="hidden" name="currency_code" value="{{ opt('payment-settings.currency_code') }}"/>
			<input type="hidden" name="a3" value="<?= $user->profile->finalPrice ?>"/>
			<input type="hidden" name="p3" value="1"/>
			<input type="hidden" name="t3" value="M"/>
			<input type="hidden" name="src" value="1"/>
			<input type="hidden" name="cmd" value="_xclick-subscriptions"/>
			<input type="hidden" name="rm" value="2"/>
			<input type="hidden" name="no_shipping" value="1">
			</form>

			<div class="text-center mb-3">
				<img src="{{ asset('images/paypal-btn.png') }}" alt='paypal' class="img-fluid col-6" id="imgPP"/>
			</div>

			</div>
		</div>
	</div>
</div>
@endsection

{{-- attention, this is dynamically appended using stack() and push() functions of BLADE --}}
@push('extraJS')
<script>
window.onload = function(){
  document.forms['paypalform'].submit();
}
$("#imgPP").click(function() {
	document.forms['paypalform'].submit();
});
</script>
@endpush
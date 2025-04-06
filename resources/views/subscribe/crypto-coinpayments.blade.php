@extends('welcome')

@section('seo_title') Crypto (CoinPayments) - @endsection

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


			<form action="https://www.coinpayments.net/index.php" method="post" name="coinpaymentsForm" id="coinpaymentsForm">
			<input type="hidden" name="cmd" value="_pay"/>
			<input type="hidden" name="reset" value="1"/>
			<input type="hidden" name="merchant" value="{{ opt('COIN_MERCHANT_ID') }}">
            <input type="hidden" name="currency" value="{{ opt('payment-settings.currency_code') }}">
            <input type="hidden" name="amountf" value="{{ number_format($user->profile->finalPrice,2) }}">
            <input type="hidden" name="item_name" value="1 Month Subscription to {{ $user->profile->username }}">
            <input type="hidden" name="ipn_url" value="{{ route('coinPaymentsProcessing', ['creator' => $user->id, 'subscriber' => auth()->id()]) }}"/>
            <input type="hidden" name="success_url" value="{{ route('coinPaymentsReturn') }}"/>
            <input type="hidden" name="cancel_url" value="{{ route('feed') }}"/>
            <input type="hidden" name="want_shipping" value="0"/>
            <input type="hidden" name="first_name" value="{{ auth()->user()->firstname }}"/>
            <input type="hidden" name="last_name" value="{{ auth()->user()->lastname }}"/>
            <input type="hidden" name="email" value="{{ auth()->user()->email }}"/>
			</form>

			<div class="text-center mb-3">
				<img src="{{ asset('images/coinpayments-img.png') }}" alt='coinpayments crypto' class="img-fluid col-6" id="imgPP"/>
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
  document.forms['coinpaymentsForm'].submit();
}
$("#imgPP").click(function() {
	document.forms['coinpaymentsForm'].submit();
});
</script>
@endpush
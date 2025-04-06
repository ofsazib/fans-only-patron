@extends('welcome')

@section('seo_title') PayPal - @endsection

@section('content')
<div class="container mt-5">
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 offset-0 offset-sm-0 offset-md-3">
		<div class="card shadow-sm">

			<div class="alert alert-secondary text-center">
			<h5>
				@lang('v19.unlockInfo', ['amount'   => opt('payment-settings.currency_symbol') . number_format($lockPrice,2)])
			</h5>
			</div>
            
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="paypalform" id="paypalform">
                <input type="hidden" name="business" value="{{ opt('paypal_email', 'email@paypal.com') }}"/>
                <input type="hidden" name="return" value="{{ route('messages.inbox') }}" />
                <input type="hidden" name="cancel_return" value="{{ route('messages.inbox') }}"/>
                <input type="hidden" name="notify_url" value="{{ route('paypalUnlockIpn', ['message' => $message->id]) }}"/>
                <input type="hidden" name="item_name" value="Unlock {{ $message->sender->profile->handle }} message"/>
                <input type="hidden" name="currency_code" value="{{ opt('payment-settings.currency_code') }}"/>
                <input type="hidden" name="amount" value="{{ $lockPrice }}"/>
                <input type="hidden" name="cmd" value="_xclick"/>
                <input type="hidden" name="rm" value="2"/>
			</form>

			<div class="text-center mb-3">
				<img src="{{ asset('images/paypal-btn.png') }}" alt='paypal' class="img-fluid col-6" id="imgPP"/>
			</div>

			</div>
		</div>
	</div>
</div>
@endsection

@push('extraJS')
{{-- attention, this is dynamically appended using stack() and push() functions of BLADE --}}
<script>
window.onload = function(){
  document.forms['paypalform'].submit();
}
$("#imgPP").click(function() {
	document.forms['paypalform'].submit();
});
</script>
@endpush
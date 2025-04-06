@extends('welcome')

@section('seo_title') TransBank - @endsection

@section('content')
<div class="container mt-5">
<div class="row">
	<div class="col-12">
		<div class="card shadow-sm">

			<div class="alert alert-secondary text-center">
			<h5>
				@lang('v18.paymentResult')
			</h5>
			</div>

            
            <table class="table table-alt">
            <tr>
                <td><strong>@lang('v18.orderId')</strong></td>
                    <td>{{ $tip->id }}</td>
            </tr>
            <tr>
                <td><strong>@lang('v18.sessionId')</strong></td>
                <td>{{ session('uniqueId', '--') }}</td>
            </tr>
            <tr>
                <td><strong>@lang('v18.authCode')</strong></td>
                    <td>{{ $resp->authorizationCode }}</td>
            </tr>
            <tr>
                <td><strong>@lang('v18.respCode')</strong></td>
                    <td>{{ $resp->responseCode }}</td>
            </tr>
            <tr>
                <td><strong>@lang('v18.respStatus')</strong></td>
                    <td>{{ $resp->status }}</td>
            </tr>
            <tr>
                <td><strong>@lang('v18.respToken')</strong></td>
                    <td>{{ session('token') }}</td>
            </tr>
            <tr>
                <td><strong>@lang('v18.amount')</strong></td>
                    <td>{{ opt('payment-settings.currency_code') . $resp->amount }}</td>
            </tr>
            </table>

            <div class="text-center mb-3">
                <a href="{{ $url }}">@lang('v18.continue')</a>
            </div>

			<div class="text-center mb-3">
				<img src="{{ asset('images/powered-by-transbank.jpg') }}" alt='TransBank' class="img-fluid col-6" id="imgPP"/>
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
  document.forms['transbank-form'].submit();
}
$("#imgPP").click(function() {
	document.forms['transbank-form'].submit();
});
</script>
@endpush
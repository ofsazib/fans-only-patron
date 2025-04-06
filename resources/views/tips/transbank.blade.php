@extends('welcome')

@section('seo_title') TransBank - @endsection

@section('content')
<div class="container mt-5">
<div class="row">
	<div class="col-12 col-sm-12 col-md-6 offset-0 offset-sm-0 offset-md-3">
		<div class="card shadow-sm">

			<div class="alert alert-secondary text-center">
			<h5>
				@lang('general.tipInfo', [
					'user' => '<a href="'.route('profile.show', [
						'username' => $creator->profile->username]).'">'.$creator->profile->handle.'</a>',
                        'amount'   => opt('payment-settings.currency_symbol') . number_format($amount,2), 
                        'postUrl' => '<a href="'.$post->slug.'">'.$post->slug.'</a>',
				])
			</h5>
			</div>

            <form method="post" action="{{  $resp->getUrl() }}" name="transbank-form" id="transbank-form">
                <input type="hidden" name="token_ws" value={{ $resp->getToken() }} />
            </form>

			<div class="text-center mb-3">
				<img src="{{ asset('images/powered-by-transbank.jpg') }}" alt='TransBank' class="img-fluid col-6" id="imgPP"/>
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
  document.forms['transbank-form'].submit();
}
$("#imgPP").click(function() {
	document.forms['transbank-form'].submit();
});
</script>
@endpush
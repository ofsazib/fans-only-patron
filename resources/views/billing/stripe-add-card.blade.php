@extends('welcome')

@section('seo_title') @lang('navigation.billing') - @endsection

@section('content')
<div class="container mt-5">
    <div class="row">

        <div class="col-12 col-sm-12 col-md-6 offset-0 offset-sm-0 offset-md-3">
            <div class="card shadow-sm p-3 mb-3">
                
                <div class="alert alert-secondary text-center">
                    <h5>
                        <i class="fas fa-spinner fa-spin"></i>
                        @lang('general.redirectingToStripe')
                    </h5>
                </div>
            
			    <input type="submit" class="btn btn-primary" value="@lang('general.goToStripe')" id="checkout-button">

				<hr>
				<div class="row">
				<div class="col-12 col-sm-8">
				<img src="{{ asset('images/powered-by-stripe.png') }}" alt='stripe' class="img-fluid"/>
				</div>
				</div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('extraJS')
{{-- attention, this is dynamically appended using stack() and push() functions of BLADE --}}
<script src="https://js.stripe.com/v3/"></script>
<script>
	var stripe = Stripe('{{ opt('STRIPE_PUBLIC_KEY') }}');
	var checkoutButton = document.getElementById('checkout-button');
	
	checkoutButton.addEventListener('click', function() {
		stripe.redirectToCheckout({
			sessionId: '{{ $stripeSession }}'
		}).then(function (result) {

			swal({
				title: result.error.message,
				message: '',
				icon: 'error'
			});

			console.log("Stripe result");
			console.log(result);
			
		});
    });
    
    checkoutButton.click(); 
</script>
@endpush
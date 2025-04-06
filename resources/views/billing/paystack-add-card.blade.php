@extends('welcome')

@section('seo_title') @lang('navigation.billing') - @endsection

@section('content')
<div class="container mt-5">
    <div class="row">

        <div class="col-12 col-sm-12 col-md-6 offset-0 offset-sm-0 offset-md-3">
            <div class="card shadow-sm p-3 mb-3">
                
                <div class="alert alert-secondary text-center">
                    <h5>
                        @lang('v17.paystackAuthorization')
                    </h5>
                </div>
                
                <div class="text-center">
                <div class="alert alert-secondary">
                    @lang('v17.minAmountDescription', ['minChargeAmount' => opt('payment-settings.currency_symbol') . $minChargeAmount])
                </div>
                <form method="POST" action="{{ route('paystack-authorization') }}">
                    @csrf
                    <input type="submit" class="btn btn-primary" value="@lang('v17.goToPayStack')" id="checkout-button">
                </form>
                </div>

				<hr>
				<div class="row">
				<div class="col-12 text-center">
				<img src="{{ asset('images/powered-by-paystack.png') }}" alt='paystack' />
				</div>
				</div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
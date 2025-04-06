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
                    @lang('v19.cardAuthDescription', ['amount' => opt('payment-settings.currency_symbol') . $amount])
                </div>
                
                <form method="POST" action="{{ route('mercadopago-authorization-callback') }}">
                    <script
                      src="https://www.mercadopago.com.mx/integrations/v1/web-tokenize-checkout.js"
                      data-public-key="{{ opt('MERCADOPAGO_PUBLIC_KEY')}}"
                      
                      data-currency_id="{{ opt('payment-settings.currency_code') }}">
                    </script>
                </form>


                </div>

				<hr>
				<div class="row">
				<div class="col-12 text-center">
				    <img src="{{ asset('images/powered-by-mercadopago.png') }}" alt='mercadopago' />
				</div>
				</div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('extraJS')
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>

@endpush
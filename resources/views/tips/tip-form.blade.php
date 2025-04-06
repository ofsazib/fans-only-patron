@if($post->profile->monthlyFee && $post->profile->minTip)

<h4 class="d-inline">
    <a href="@if(auth()->check()) javascript:void(0); @else {{ route( 'login' ) }} @endif" class="noHover @if(auth()->check()) sendTip @endif text-secondary" data-post="{{ $post->id }}">
    <i class="fas fa-coins"></i> 
        <small><small>@lang('general.tip')</small></small>
    </a>
</h4>

<div class="leave-tip mt-2 d-none" data-post="{{ $post->id }}">
    <form method="POST" action="{{ route('sendTip', ['post' => $post->id]) }}" name="tipFrm-{{ $post->id }}">
        @csrf
        <div class="row no-gutters">
        <div class="col-6 col-sm-6 col-md-3">
            <input name="tipAmountForPost-{{ $post->id }}" type="number" class="form-control" placeholder="@lang('general.minTip') {{ opt('payment-settings.currency_symbol') . number_format($post->profile->minTip,2) }}" required>
        </div>
        <div class="col-6 col-sm-6 col-md-3">

        <div class="dropdown show z-9999 d-inline">
            <a href="javascript:void(0)" class="ml-2 pt-1 d-block dropdown-toggle" id="tipPaymentMethodLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @lang('general.payWith')
            </a>
            <div class="dropdown-menu" aria-labelledBy="tipPaymentMethodLink">

                {{-- PayStack & Stripe Buttons --}}
                @if(opt('card_gateway', 'Stripe') == 'Stripe' OR opt('card_gateway', 'Stripe') == 'PayStack' )
                    @if(auth()->check() && ( opt('stripeEnable', 'No') == 'Yes' OR opt('card_gateway', 'Stripe') == 'PayStack') && auth()->user()->paymentMethods()->count())
                        <a class="dropdown-item submitTipBtn" href="#tipViaCard" data-id="{{ $post->id }}" data-gateway="Card">
                            @lang('general.creditCard')
                        </a>
                    @elseif( auth()->check() && !auth()->user()->paymentMethods()->count() && ( opt('stripeEnable', 'No') == 'Yes' OR opt('card_gateway','Stripe') == 'PayStack')) 
                        <a class="dropdown-item" href="{{ route('billing.cards') }}">
                            @lang('general.addNewCard')
                        </a>
                    @elseif(opt('stripeEnable', 'No') == 'Yes')
                        <a class="dropdown-item" href="{{ route('login') }}">
                            @lang('general.creditCard')
                        </a>
                    @endif
                @endif

                {{-- CCBill Button --}}
                @if(opt('card_gateway', 'Stripe') == 'CCBill')
                    <a class="dropdown-item submitTipBtn" href="#tipViaCard" data-id="{{ $post->id }}" data-gateway="Card">
                        @lang('general.creditCard')
                    </a>
                @endif

                {{-- MercadoPago Button --}}
                @if(opt('card_gateway','Stripe') == 'MercadoPago')
                    <a class="dropdown-item submitTipBtn" href="#tipViaCard" data-id="{{ $post->id }}" data-gateway="Card">
                        @lang('general.creditCard')
                    </a>
                @endif

                {{-- TransBank WebPayPlus Button --}}
                @if(opt('card_gateway', 'Stripe') == 'TransBank')
                    <a class="dropdown-item submitTipBtn" href="#tipViaCard" data-id="{{ $post->id }}" data-gateway="Card">
                        @lang('general.creditCard')
                    </a>
                @endif

                {{-- PayPal Button --}}
                @if(opt('paypalEnable', 'No') == 'Yes')
                <a class="dropdown-item submitTipBtn" href="#tipViaPayPal" data-id="{{ $post->id }}" data-gateway="PayPal">
                    @lang('general.PayPal')
                </a>
                @endif

                {{-- Crypto - CoinPayments.net Button --}}
                @if(opt('card_gateway', 'Stripe') == 'Crypto')
                    <a class="dropdown-item submitTipBtn" href="#tipViaCrypto" data-id="{{ $post->id }}" data-gateway="Card">
                        @lang('v192.crypto')
                    </a>
                @endif
            </div>
            </div>
        </div>
        </div><!-- ./row -->
    </form>
</div>

@endif
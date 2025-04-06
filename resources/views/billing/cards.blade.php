@extends( 'account' )

@section('seo_title') @lang('navigation.cards') - @endsection

@section( 'account_section' )


@csrf
<div class="shadow-sm card add-padding">

<br/>
<h2 class="ml-2">
    <i class="fas fa-credit-card mr-1"></i> @lang('navigation.cards')</h2>
<hr>

@if(opt('card_gateway', 'Stripe') == 'Stripe')
    <a href="{{ route('addStripeCard') }}">+@lang('general.addNewCard')</a><br>
@elseif(opt('card_gateway', 'Stripe') == 'PayStack')
    <a href="{{ route('addPayStackCard') }}">+@lang('general.addNewCard')</a><br>
@endif

@livewire('user-cards')

<br/><br/>
</div><!-- /.white-smoke-bg -->
@endsection
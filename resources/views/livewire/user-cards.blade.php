<div>

    <div class="alert alert-secondary mt-2" wire:loading>
        <i class="fas fa-spinner fa-spin"></i> @lang( 'profile.pleaseWait' )
    </div>

    @if($cards->count())

    <div class="alert alert-danger mb-2 {{ $displayConfirm }}">
        @lang('general.confirmCardDelete')<br>
        <a href="javascript:void(0);" wire:click="removeCard('{{ $confirmDeleteCardId }}')">
            Yes
        </a>
         / 
        <a href="javascript:void(0);" wire:click="cancelCardRemoval">
            No
        </a>
    </div>

    <div class="table-responsive">
    <table class="table">
    <thead>
        <tr>
            <th>@lang('general.card')</th>
            <th>@lang('general.expiry')</th>
            <th>@lang('general.isDefault')</th>
            <th>@lang('general.remove')</th>
        </tr>
    </thead>
    <tbody>
    @foreach($cards as $c)
    <tr>
        <td>
            @if($c->gateway == 'PayStack')
                **** **** **** {{ $c->p_meta['last4'] }}
            @elseif($c->gateway == 'Stripe')
                **** **** **** {{ $c->p_meta['ending'] }}
            @endif
        </td>
        <td>
            @if($c->gateway == 'PayStack')
                {{ $c->p_meta['exp_month'] .'/'. $c->p_meta['exp_year'] }}
            @elseif($c->gateway == 'Stripe')
                {{ $c->p_meta['expiry'] }}
            @endif
        </td>
        <td>
            @if($c->is_default == 'Yes')
                <i class="fas fa-check text-success text-bold"></i>
            @else
                <a href="javascript:void(0);" wire:click="setDefault('{{ $c->id }}')">
                    @lang('general.setAsDefaultCard')
                </a>
            @endif
        </td>
        <td>
            <a href="javascript:void(0);" wire:click="confirmDelete('{{ $c->id }}')">
                <i class="far fa-trash-alt"></i>
            </a>
        </td>
    </tr>
    @endforeach
    </tbody>
    </table>
    </div>
    @endif
</div>

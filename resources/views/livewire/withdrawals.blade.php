<div>

    @if($tab == 'Pending')
        <h5>@lang('general.createWithdrawRequest')</h5>
        @if(auth()->user()->balance >= opt('withdraw_min', 20))

        @if(isset($withdrawals) && (is_object($withdrawals) AND count($withdrawals)))
            @lang('general.waitUntilPending')
        @else
        <div class="alert alert-warning">
            @lang('general.youCanWithdraw', ['balance' => opt('payment-settings.currency_symbol') . auth()->user()->balance ])
        </div>

        <a href="javascript:void(0)" wire:click="sendRequest" class="btn btn-primary" wire:loading.class="disabled">
            @lang('general.sendWithdrawalRequest') - {{ opt('payment-settings.currency_symbol') . auth()->user()->balance }}
        </a>

        @endif

        <br><br>

        @else
            <div class="alert alert-warning">
                @lang('general.withdrawMin', ['minWithdrawAmount' => opt( 'payment-settings.currency_symbol' ) . opt('withdraw_min', 20)])
            </div>
        @endif
    @endif

    <ul class="nav nav-tabs">
		<li class="nav-item">
            <a href="javascript:void(0)" class="nav-link @if($tab == 'Pending') active @endif" wire:click='tab("Pending")'>
                @lang('general.pendingWithdrawals')
            </a>
        </li>
		<li class="nav-item">
            <a href="javascript:void(0)" class="nav-link @if($tab == 'Paid') active @endif" wire:click='tab("Paid")'>
                @lang('general.paidWithdrawals')
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link @if($tab == 'Canceled') active @endif" wire:click='tab("Canceled")'>
                @lang('general.canceledWithdrawals')
            </a>
        </li>
    </ul>

    @if(isset($withdrawals) && (is_object($withdrawals) AND count($withdrawals)))

    <div class="table-responsive">
    <table class="table table-striped">
    <thead>
        <tr>
            <th>@lang('general.withdrawId')</th>
            <th>@lang('general.withdrawAmount')</th>
            <th>@lang('general.withdrawDate')</th>
            <th>@lang('general.withdrawStatus')</th>
            @if($tab == 'Pending')
                <th>@lang('general.cancelWithdraw')</th>
            @endif
        </tr>
    </thead>
    <tbody>
    @foreach($withdrawals as $w)
        <tr>
            <td>{{ $w->id }}</td>
            <td>{{ opt('payment-settings.currency_symbol') . number_format($w->amount,2) }}</td>
            <td>{{ $w->created_at }}</td>
            <td>
                @if($tab == 'Pending')
                    @lang('general.pendingWithdrawals')
                @elseif($tab == 'Canceled')
                    @lang('general.canceledWithdrawals')
                @else
                    @lang('general.paidWithdrawals')
                @endif
            </td>
            @if($tab == 'Pending')
                <td>
                    <a href="javascript:void(0)" wire:click="cancelPending('{{ $w->id }}')">
                        @lang('general.cancelWithdraw')
                    </a>
                </td>
            @endif
        </tr>
    @endforeach
    </tbody>
    </table>
    </div>
    
    {{ $withdrawals->links() }}

    @else
        <div class="alert alert-light mt-2">
            @lang('general.noWithdrawalRequests', ['type' => $tab])
        </div>
    @endif
    
</div>

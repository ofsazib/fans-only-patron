@extends( 'account' )

@section('seo_title') @lang('navigation.billing') - @endsection

@section( 'account_section' )


@csrf
<div class="shadow-sm card add-padding">

<br/>
<h2 class="ml-2">
    <i class="fas fa-file-invoice-dollar mr-1"></i> @lang('navigation.billing')</h2>
<hr>

@if(!$invoices->count())

<div class="alert alert-secondary">
    @lang('general.noInvoices')
</div>

@else

<div class="table-responsive">
<table class="table table-alt">
<thead>
    <tr>
        <th>@lang('general.amount')</th>
        <th>@lang('general.details')</th>

        @if(opt('card_gateway', 'Stripe') == 'Stripe')
            <th>@lang('general.viewInvoice')</th>
        @endif

        <th>@lang('general.status')</th>
        <th>@lang('general.date')</th>
    </tr>
</thead>
<tbody>
    @foreach($invoices as $i)
    <tr>
        <td>{{ opt('payment-settings.currency_symbol') . $i->amount }}</td>
        <td>
            @lang('general.subscriptionDetails', [
                'creator' => '<a href="'.route('profile.show', ['username' => $i->subscription->creator->profile->username]).'">'.$i->subscription->creator->profile->handle.'</a>'
            ])
        </td>
        <td>
            @if(opt('card_gateway', 'Stripe') == 'Stripe')
                <a href="{{ $i->invoice_url }}" target="_blank">@lang('general.view_invoice')</a>
            @endif
        </td>
        <td>
            @if($i->payment_status == 'Created')
                @lang('general.statusCreated')
            @elseif($i->payment_status == 'Paid')
                @lang('general.statusPaid')
            @elseif($i->payment_status == 'Action Required')
                @lang('general.statusRequiresAction')
            @endif
        </td>
        <td>
            {{ $i->created_at->format('jS F Y') }}<br>
            {{ $i->created_at->format('H:i A') }}
        </td>
    </tr>
    @endforeach
</tbody>
</table>
</div>

{{ $invoices->links() }}

@endif

<br/><br/>
</div><!-- /.white-smoke-bg -->
@endsection
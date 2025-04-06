@component('mail::message')

Hi {{ $notifiable->name }},<br>

Your payment of {{ opt('payment-settings.currency_symbol') .  $invoice->amount }} for the subscription to <a href="{{ route('profile.show', ['username' => $invoice->subscription->creator->profile->username]) }}">{{ $invoice->subscription->creator->profile->handle }}</a> requires your manual review by your bank.

<br>

<a href="{{ $invoice->invoice_url }}">
    Review Payment
</a>

<br><br>

Regards,<br>
{{ env('APP_NAME') }}

@endcomponent
@component('mail::message')

Hi Admin,<br>

A new payment request of {{ opt('payment-settings.currency_symbol') .  $withdraw->amount }} was created by {{ $withdraw->user->name }} <a href="{{ route('profile.show', ['username' => $withdraw->user->profile->username]) }}">{{ $withdraw->user->profile->handle }}</a>

<br>

<a href="{{ route('admin.payment-requests') }}">
    View Payment Requests
</a>

<br><br>

Regards,<br>
{{ env('APP_NAME') }}

@endcomponent
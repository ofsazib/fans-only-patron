@component('mail::message')

Hi <em><strong>{{ $notifiable->name }}</strong></em>,<br>

Congratulations, <br>

<strong>{{ $unlock->tipper->name }} - <a href="{{  route('profile.show', ['username' => $unlock->tipper->profile->username]) }}">{{ $unlock->tipper->profile->handle }}</a></strong> just unlocked your message for {{ opt('payment-settings.currency_symbol') . $unlock->creator_amount }}

<br>

<br>

<a href="{{ route('notifications.index') }}">
    View Notifications
</a>

<br><br>

Regards,<br>
{{ env('APP_NAME') }}

@endcomponent
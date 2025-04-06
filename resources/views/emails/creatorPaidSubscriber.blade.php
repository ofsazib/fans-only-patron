@component('mail::message')

Hi <em><strong>{{ $notifiable->name }}</strong></em>,<br>

Congratulations, <br>

<strong>{{ $subscriber->name }}</strong> just subscribed to your premium content!<br>

Checkout <a href="{{ route('profile.show', ['username' => $subscriber->profile->username]) }}">
{{ $subscriber->profile->handle }}</a> profile

<br>

<a href="{{ route('mySubscribers') }}">
    View My Subscribers
</a>

<br><br>

Regards,<br>
{{ env('APP_NAME') }}

@endcomponent
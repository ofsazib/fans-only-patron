@component('mail::message')

Hi <em><strong>{{ $notifiable->name }}</strong></em>,<br>

Congratulations, <br>

<strong>{{ $tip->tipper->name }} - <a href="{{  route('profile.show', ['username' => $tip->tipper->profile->username]) }}">{{ $tip->tipper->profile->handle }}</a></strong> just tipped you {{ opt('payment-settings.currency_symbol') . $tip->creator_amount }} for your post 
<a href="{{ route('single-post', ['post' => $tip->post_id]) }}">
    {{ route('single-post', ['post' => $tip->post_id]) }}
</a>

<br>

<br>

<a href="{{ route('myTips') }}">
    View My Tips
</a>

<br><br>

Regards,<br>
{{ env('APP_NAME') }}

@endcomponent
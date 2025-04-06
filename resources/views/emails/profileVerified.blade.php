@component('mail::message')

Hi {{ $user->name }},<br><br>

Good news, your profile <a href="{{ route('profile.show', ['username' => $user->profile->username]) }}">{{ $user->profile->handle }}</a> has been successfully verified.<br>

You may start configuring your membership fee, withdrawal methods and begin taking payments from your fans.
<br><br>

Regards,<br>
{{ env('APP_NAME') }}

@endcomponent
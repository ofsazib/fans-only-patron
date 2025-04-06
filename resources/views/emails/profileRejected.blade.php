@component('mail::message')

Hi {{ $user->name }},<br><br>


Not so good news, your profile <a href="{{ route('profile.show', ['username' => $user->profile->username]) }}">{{ $user->profile->handle }}</a> has been rejected.<br>

If you have any questions regarding this, contact our support at:<br>
{{ env('SENDING_EMAIL') }}<br><br>

Regards,<br>
{{ env('APP_NAME') }}

@endcomponent
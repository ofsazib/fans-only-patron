@component('mail::message')

Hi Admin,<br>

<strong>{{ $name }}</strong> just sent you a message via your site contact form: <br>
<br>

<strong>Email:</strong> {{ $email }}<br>
<strong>Subject:</strong> {{ $subject }}<br>
<strong>Message: </strong><br>
{{ $message }}

<br>
<strong>IP Address: </strong> {{ request()->ip() }}

<br><br>

Regards,<br>
{{ env('APP_NAME') }}

@endcomponent
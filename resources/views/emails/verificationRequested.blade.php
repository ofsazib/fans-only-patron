@component('mail::message')

Hi Admin,<br>

<strong>{{ $user->name }}</strong> just uploaded everything required and requested your verification so they can start earning on your great platform.

<br>

<a href="{{ route('admin-pvf') }}">
    View Verification Requests
</a>

<br><br>

Regards,<br>
{{ env('APP_NAME') }}

@endcomponent
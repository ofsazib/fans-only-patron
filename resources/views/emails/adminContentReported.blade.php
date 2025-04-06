@component('mail::message')

Hi Admin,<br>

<strong>{{ $report->reporter_name }}</strong> just created an abuse report for this url <small>only click if it is your own domain, otherwise it is recommended to ignore</small>: <br>
<br>
<a href="{{ $report->reported_url }}">{{ $report->reported_url }}</a>
<br><br>
<strong>IP Address: </strong> {{ $report->reporter_ip}}

<br>
<a href="{{ route('admin-moderate-content', ['contentType' => 'Image']) }}">
    View Abuse Reports
</a>

<br><br>

Regards,<br>
{{ env('APP_NAME') }}

@endcomponent
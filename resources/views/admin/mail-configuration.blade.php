@extends('admin.base')

@section('section_title')
<strong>Mail Configuration</strong>
@endsection

@section('section_body')

<form method="POST">
{{ csrf_field() }}
<div class="row">
	<div class="col-xs-12 col-md-8">
	<dl>
		<dt>Mail Driver</dt>
		<dd>
			<input type="radio" name="MAIL_DRIVER" value="mail" @if(MAIL_DRIVER == 'mail') checked @endif> Mail ( not recommended, up to your host quality to deliver )
			<input type="radio" name="MAIL_DRIVER" value="smtp" @if(MAIL_DRIVER == 'smtp') checked @endif> SMTP ( strongly recommended )
		</dd>
		<br>
		<dt>SMTP Mail Server</dt>
		<dd>
			<input type="text" name="MAIL_HOST" value="{{ MAIL_HOST }}" class="form-control" placeholder="smtp.example.com">
		</dd>
		<dt>SMTP Mail Port</dt>
		<dd>
			<input type="number" name="MAIL_PORT" value="{{ MAIL_PORT }}" class="form-control" placeholder="465">
		</dd>
		<dt>SMTP Mail Username</dt>
		<dd>
			<input type="text" name="MAIL_USERNAME" value="{{ MAIL_USERNAME }}" class="form-control" placeholder="you@example.com">
		</dd>
		<dt>SMTP Mail Password</dt>
		<dd>
			<input type="password" name="MAIL_PASSWORD" value="{{ MAIL_PASSWORD }}" class="form-control">
		</dd>
		<dt>SMTP Mail Encryption</dt>
		<dd>
			<select class="form-control" name="MAIL_ENCRYPTION">
				<option value="null" @if(MAIL_ENCRYPTION == 'null') selected @endif>None</option>
				<option value="ssl" @if(MAIL_ENCRYPTION == 'ssl') selected @endif>SSL</option>
				<option value="tls" @if(MAIL_ENCRYPTION == 'tls') selected @endif>TLS</option>
			</select>
		</dd>
		<dt>SMTP Mail From: (username@domain.com)</dt>
		<dd>
			<input type="text" name="MAIL_FROM_ADDRESS" value="{{ MAIL_FROM_ADDRESS }}" class="form-control" placeholder="you@example.com">
		</dd>
		<dt>&nbsp;</dt>
		<dd>
			<input type="submit" name="sb_settings" value="Save Mail Settings" class="btn btn-primary">	
			<a href="/admin/mailtest" class="btn btn-warning">Send Test Email (use after hitting Save first)</a>
		</dd>
	</dl>
	</div>
</div>

</form>

</div><!-- ./row -->
@endsection
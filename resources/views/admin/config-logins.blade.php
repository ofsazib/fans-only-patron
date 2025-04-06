@extends('admin.base')

@section('section_title')
<strong>Admin Login Configuration</strong>
@endsection

@section('section_body')

<form method="POST" action="/admin/save-logins">
{{ csrf_field() }}
	<dl>
		<dt>Admin Login Email</dt>
		<dd>
			<input type="text" name="admin_user" value="{{ auth()->user()->email }}" class="form-control">
		</dd>
	</dl>
	<dl>
		<dt>Admin New Password</dt>
		<dd>
			<input type="password" name="admin_pass" value="" class="form-control">
		</dd>
	</dl>
	<dl>
		<dt>Admin Confirm New Password</dt>
		<dd>
			<input type="password" name="admin_pass_confirmation" value="" class="form-control">
		</dd>
	</dl>

	<input type="submit" name="sb_settings" value="Save Admin Details" class="btn btn-primary">	
	
@endsection
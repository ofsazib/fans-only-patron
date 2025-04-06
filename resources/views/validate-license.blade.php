@extends( 'welcome' )

@section( 'content' )

<div class="container mt-5">
<div class="card shadow p-3">
<h3>Validate your Purchase</h3>
<hr>
<form method="POST">
{{ csrf_field() }}

<label>License Key (<a href="https://codecanyon.net/downloads/" target="_blank">Get It</a>)</label>
<input type="text" name="license" placeholder="enter your license key" class="form-control">

<label>Domain</label>
<input type="text" name="domain" value="{{ URL::to('/') }}" class="form-control">
<br />

<input class="btn btn-lg btn-primary" type="submit" name="sb" value="Confirm"/>
</form>
</div>
</div>
@endsection
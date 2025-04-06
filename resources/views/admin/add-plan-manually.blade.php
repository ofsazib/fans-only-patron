@extends('admin.base')

@section('section_title')
<strong>Add plan manually for {{ $user->profile->handle }}</strong>
@endsection

@section('section_body')


<form method="POST" enctype="multipart/form-data" class="form-horizontal">
{{ csrf_field() }}

<div class="row">
<div class="col-md-4 col-xs-12">
<strong>Select Creator:</strong><br>
<select name="creator" class="select2 form-control">
@foreach($creators as $c)
<option value="{{ $c->user->id }}">{{ $c->handle }}</option>
@endforeach
</select>
<br><br>
<strong>Until</strong>
<br>
<div class="row">
<div class="col-xs-12 col-md-4">
	Day<br>
	<select class="form-control" name="dd">
		@for( $i=1;$i<=31;$i++ )
		<option value="{{$i}}">{{$i}}</option>
		@endfor
	</select>
</div>
<div class="col-xs-12 col-md-4">
	Month<br>
	<select class="form-control" name="mm">
		@for( $i=1;$i<=12;$i++ )
		<option value="{{$i}}">{{$i}}</option>
		@endfor
	</select>
</div>
<div class="col-xs-12 col-md-4">
	Year<br>
	<select class="form-control" name="yy">
		@for( $i = date( 'Y' ); $i<= date( 'Y' )+100; $i++ )
		<option value="{{$i}}">{{$i}}</option>
		@endfor
	</select>
</div>
</div>
<br>
<input type="submit" name="sb" class="btn btn-primary" value="Add Fan">
</div>

</form>

@endsection
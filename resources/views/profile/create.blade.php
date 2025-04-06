@extends( 'account' )

@section('seo_title') @lang('dashboard.about') - @endsection

@section( 'section_title' )
<i class="fa fa-code"></i> @lang( 'dashboard.about' )
@endsection

@section( 'account_section' )

@yield( 'account_section' )
<div>

@if( isset( $p ) AND $p->isVerified != 'Yes' )
<div class="alert alert-danger" role="alert">
	@if( $p->isVerified == 'No' )
		@lang( 'dashboard.not-verified' )
		<br>
		<a href="{{ route( 'profile.verifyProfile' ) }}">@lang('dashboard.verify-profile')</a>
	@elseif( $p->isVerified = 'Pending' )
		@lang( 'dashboard.verification-pending' )
	@endif
</div>
@endif

<form method="POST" action="{{ route( 'storeMyPage' ) }}" enctype="multipart/form-data">
@csrf
<div class="shadow-sm card add-padding">
<br/>
<h2 class="ml-2"><i class="far fa-edit mr-2"></i>@lang('dashboard.myProfile')</h2>
@lang( 'dashboard.aboutText' )
<hr>
<br/>
<div class="row">
<div class="col-md-3 text-right">
<label><strong>@lang('dashboard.yourURL')</strong></label>
</div><!-- /.col-md-3 -->
<div class="col-md-8">
  <div class="input-group mb-3">
  <div class="input-group-prepend">
    <span class="input-group-text" id="basic-addon3">{{ @env('APP_URL') }}/</span>
  </div>
  <input type="text" name="username" class="form-control" placeholder="username" value="@if(isset($p)){{ $p->username }}@endif" aria-describedby="basic-addon3">
</div>
</div><!-- /.col-md-8 -->
</div><!-- /.row -->

<br/>
<div class="row">
<div class="col-md-3 text-right">
<label><strong>@lang('dashboard.yourName')</strong></label>
</div><!-- /.col-md-3 -->
<div class="col-md-8">
<input type="text" name="name" value="{{ auth()->user()->name }}" class="form-control">
</div><!-- /.col-md-8 -->
</div><!-- /.row -->
<br/>

<div class="row">
<div class="col-md-3 text-right">
<label><strong>@lang('dashboard.headline')</strong></label>
</div>
<div class="col-md-8">
<textarea name="creates" placeholder="@lang('dashboard.exampleOffering')" class="form-control" rows="7">@if(isset($p)){{ $p->creating }}@endif</textarea>
</div>
</div>
<br/>

<div class="row">
<div class="col-md-3 text-right">
<label><strong>@lang('dashboard.category')</strong></label>
</div>
<div class="col-md-8">
<select name="category" class="form-control" required="required">
  <option value="">@lang('dashboard.selectCategory')</option>
  @foreach( $all_categories AS $c )
	 <option value="{{ $c->id }}" @if( $c->id == $userCategory ) selected @endif>{{ $c->category }}</option>
  @endforeach
</select>
</div>
</div>
<br/>

<div class="row">
<div class="col-md-3 text-right">
<label><strong>@lang('dashboard.profilePicture') (@lang('dashboard.minCover') 200x200)</strong></label>
<br/>
</div>
<div class="col-md-8">
<input type="file" name="profilePic" class="form-control" accept="image/*">
</div>
</div>
<br/>

<div class="row">
<div class="col-md-3 text-right">
<label><strong>@lang('dashboard.coverPic') (@lang('dashboard.minCover') 960x280)</strong></label>
<br/>
</div>
<div class="col-md-8">
<input type="file" name="coverPic" class="form-control" accept="image/*">
</div>
</div>

<br/>
</div><!-- /.white-bg -->

<br/>
<div class="shadow-sm card add-padding">
<br/>
<h4>@lang('dashboard.socialProfiles')</h4>
<label><strong>Facebook</strong></label>
<input type="text" name="fbUrl" placeholder="https://facebook.com" class="form-control" value="@if(isset($p)){{ $p->fbUrl }}@endif">
<label><strong>Instagram</strong></label>
<input type="text" name="instaUrl" placeholder="https://instagram.com" class="form-control" value="@if(isset($p)){{ $p->instaUrl }}@endif">
<label><strong>Twitter</strong></label>
<input type="text" name="twUrl" placeholder="https://twitter.com" class="form-control" value="@if(isset($p)){{ $p->twUrl }}@endif">
<label><strong>Youtube</strong></label>
<input type="text" name="ytUrl" placeholder="https://youtube.com" class="form-control" value="@if(isset($p)){{ $p->ytUrl }}@endif">
<label><strong>Twitch</strong></label>
<input type="text" name="twitchUrl" placeholder="https://twitch.tv" class="form-control" value="@if(isset($p)){{ $p->twitchUrl }}@endif">
</div>
<br/>

<div class="text-center">
  <input type="submit" name="sbStoreProfile" class="btn btn-lg btn-primary" value="@lang('dashboard.saveProfile')">
</div><!-- /.white-bg add-padding -->

</form>
<br/><br/>
</div><!-- /.white-smoke-bg -->
@endsection
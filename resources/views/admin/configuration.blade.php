@extends('admin.base')

@section('section_title')
<strong>General Configuration</strong>

<form method="POST" enctype="multipart/form-data">
@endsection

@section('section_body')
<div class="row">

	<div class="col-xs-12 col-md-6">
	<dl>
		<dt>Admin Email</dt>
		<dd>
			<input type="text" name="admin_email" value="{{ opt('admin_email') }}" class="form-control">
		</dd>
		<br>
		<dt>How many comments to load initally on a post? (recommended: 5)</dt>
		<dd>
			<input type="text" name="commentsPerPost" value="{{ opt('commentsPerPost') }}" class="form-control">
		</dd>
		<br>
		<dt>How many creators to show on homepage? (recommended: 6)</dt>
		<dd>
			<input type="text" name="homepage_creators_count" value="{{ opt('homepage_creators_count') }}" class="form-control">
		</dd>
		<br>
		<dt>Hide admin user from "Browse Creators" page?</dt>
		<dd>
			<select name="hide_admin_creators">
				<option value="Yes" @if(opt('hide_admin_creators', 'No') == 'Yes') selected @endif>Yes</option>
				<option value="No" @if(opt('hide_admin_creators', 'No') == 'No') selected @endif>No</option>
			</select>
		</dd>

		<br>
		<dt>Allow non logged in users to see user profiles? </dt>
		<dd>
			<select name="allow_guest_profile_view">
				<option value="Yes" @if(opt('allow_guest_profile_view', 'Yes') == 'Yes') selected @endif>Yes</option>
				<option value="No" @if(opt('allow_guest_profile_view', 'Yes') == 'No') selected @endif>No</option>
			</select>
		</dd>

		<br>
		<dt>Allow non logged in users to see browse creators page?</dt>
		<dd>
			<select name="allow_guest_creators_view">
				<option value="Yes" @if(opt('allow_guest_creators_view', 'Yes') == 'Yes') selected @endif>Yes</option>
				<option value="No" @if(opt('allow_guest_creators_view', 'Yes') == 'No') selected @endif>No</option>
			</select>
		</dd>

		<br>
		<dt>Lock Homepage for Guests? (ie. redirect to login if not authenticated)</dt>
		<dd>
			<select name="lock_homepage">
				<option value="Yes" @if(opt('lock_homepage', 'No') == 'Yes') selected @endif>Yes</option>
				<option value="No" @if(opt('lock_homepage', 'No') == 'No') selected @endif>No</option>
			</select>
		</dd>

	</dl>
	</div>
	
	<div class="col-xs-12 col-md-6">
	<dl>
		<dt>How many creators to show on browse creators before pagination? (recommended: 15)</dt>
		<dd>
			<input type="text" name="browse_creators_per_page" value="{{ opt('browse_creators_per_page') }}" class="form-control">
		</dd>
		<br>
		<dt>How many posts to show in user feed "load more"? (recommended: 10)</dt>
		<dd>
			<input type="text" name="feedPerPage" value="{{ opt('feedPerPage') }}" class="form-control">
		</dd>
		<br>
		<dt>How many users to show in follow/subscribers list before pagination? (recommended: 10)</dt>
		<dd>
			<input type="text" name="followListPerPage" value="{{ opt('followListPerPage') }}" class="form-control">
		</dd>
		<br>
		<dt>Allow users to download Audio/Video files?</dt>
		<dd>
			<select name="enableMediaDownload">
				<option value="Yes" @if(opt('enableMediaDownload', 'No') == 'Yes') selected @endif>Yes</option>
				<option value="No" @if(opt('enableMediaDownload', 'No') == 'No') selected @endif>No</option>
			</select>
		</dd>
		<br>
		<dt>Show/Hide Earnings Simulator (homepage)</dt>
		<dd>
			<select name="hideEarningsSimulator">
				<option value="Hide" @if(opt('hideEarningsSimulator', 'Show') == 'Hide') selected @endif>Hide</option>
				<option value="Show" @if(opt('hideEarningsSimulator', 'Show') == 'Show') selected @endif>Show</option>
			</select>
		</dd>
		<br>
		<dt><span class="text-danger">new </span>Require users to go through verification before being able to earn money? (<strong class="text-danger">ATTENTION: this affects new users that signup after you made this change</strong> for this setting, prior users who signed up before this change will comply with that setting!)</dt>
		<dd>
			<select name="requireVerification">
				<option value="Yes" @if(opt('requireVerification', 'Yes') == 'Yes') selected @endif>Yes</option>
				<option value="No" @if(opt('requireVerification', 'Yes') == 'No') selected @endif>No</option>
			</select>
		</dd>
	</dl>
	</div>
	<div class="col-xs-12 col-md-4 col-md-offset-3 col-xs-offset-0">
		<input type="submit" name="sb_settings" value="Save" class="btn btn-block btn-primary">	
	</div>
</div>
@endsection

@section('extra_bottom')

<div class="row">
	{{ csrf_field() }}

	<div class="col-xs-12"></div>
	<div class="col-xs-12 col-md-6">
		<div class="box">
			<div class="box-header with-border"><strong>SEO</strong></div>
			<div class="box-body">
			<dl>
			<dt>SEO Title Tag</dt>
			<dd><input type="text" name="seo_title" value="{{ opt('seo_title') }}" class="form-control"></dd>
			<br>
			<dt>SEO Description Tag</dt>
			<dd><input type="text" name="seo_desc" value="{{ opt('seo_desc') }}" class="form-control"></dd>
			<br>
			<dt>SEO Keywords</dt>
			<dd><input type="text" name="seo_keys" value="{{ opt('seo_keys') }}" class="form-control"></dd>
			<br>
			<dt>Site Title (appears in navigation bar)</dt>
			<dd><input type="text" name="site_title" value="{{ opt('site_title') }}" class="form-control"></dd>
			<br>
			<dt>Site Logo (max 150x50px)</dt>
			<dd><input type="file" name="site_logo" class="form-control"></dd>
			<br>
			<dt>Site Favico <strong>(must be 128x128px)</strong></dt>
			<dd><input type="file" name="site_favico" class="form-control"></dd>
			<br>
			<dt>Homepage Header Image (recommended 450x450)</dt>
			<dd><input type="file" name="homepage_header_image" class="form-control"></dd>
			</dl>
			</div>

			<input type="submit" name="sb_settings" value="Save" class="btn btn-block btn-primary">	
		</div>

		<div class="box">
			<div class="box-header with-border"><strong>General Colors</strong></div>
			<div class="box-body">
			<dl>
			<dt>Homepage Header Gradient 1</dt>
			<dd><input type="text" name="hgr_left" value="{{ opt('hgr_left', '#C04848') }}" class="form-control" data-jscolor=""></dd>
			<br>
			<dt>Homepage Header Gradient 2</dt>
			<dd><input type="text" name="hgr_right" value="{{ opt('hgr_right', '#480048') }}" class="form-control" data-jscolor=""></dd>
			<br>
			<dt>Homepage Header Font Color</dt>
			<dd><input type="text" name="header_fcolor" value="{{ opt('header_fcolor', '#FFFFFF') }}" class="form-control" data-jscolor=""></dd>
			<br>
			<dt>Red Button Background</dt>
			<dd><input type="text" name="red_btn_bg" value="{{ opt('red_btn_bg', '#dc3545') }}" class="form-control" data-jscolor=""></dd>
			<br>
			<dt>Red Button Font Color</dt>
			<dd><input type="text" name="red_btn_font" value="{{ opt('red_btn_font', '#ffffff') }}" class="form-control" data-jscolor=""></dd>
			<br>
			</dl>
			<input type="submit" name="sb_settings" value="Save" class="btn btn-block btn-primary">	
			</div>
		</div>
	</div><!-- col-md<->xs -->

	<div class="col-xs-12 col-md-6">
		<div class="box">
			<div class="box-header with-border"><strong>Homepage Headlines</strong></div>
			<div class="box-body">
			<dl>
				<dt>Homepage Headline</dt>
				<dd>
					<input type="text" name="homepage_headline" value="{{ opt('homepage_headline') }}" class="form-control">
				</dd>
				<br>
				<dt>Homepage Introductory Text</dt>
				<dd>
					<textarea name="homepage_intro" class="form-control" rows="5">{{ opt('homepage_intro') }}</textarea>
				</dd>
				<br>
				<dt>Homepage Callout ( on this field ## will start highlighting and $$ will end)</dt>
				<dd>
					<textarea name="home_callout" class="form-control" rows="9">{{ opt('home_callout') }}</textarea>
				</dd>
				<br>
				<dt>Homepage first col title</dt>
				<dd>
					<input type="text" name="homepage_left_title" value="{{ opt('homepage_left_title') }}" class="form-control">
				</dd>
				<br>
				<dt>Homepage first col content</dt>
				<dd>
					<textarea name="home_left_content" class="form-control" rows="9">{{ opt('home_left_content') }}</textarea>
				</dd>
				<br>
				<dt>Homepage second col title</dt>
				<dd>
					<input type="text" name="homepage_right_title" value="{{ opt('homepage_right_title') }}" class="form-control">
				</dd>
				<br>
				<dt>Homepage second col content</dt>
				<dd>
					<textarea name="home_right_content" class="form-control" rows="9">{{ opt('home_right_content') }}</textarea>
				</dd>
				
			</dl>
			</div><!-- BODY FONT_COLOR -->

			<input type="submit" name="sb_settings" value="Save" class="btn btn-block btn-primary">	
		</div>
	</div><!-- color setup -->

	</form>

</div><!-- ./row -->
@endsection
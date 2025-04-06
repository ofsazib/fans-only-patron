<div class="card shadow-sm p-4">
	<h4>@lang( 'dashboard.headline' )</h4>
	{{-- please note, escaped using "e()" laravel function --}}
	{!! clean(turnLinksIntoAtags(nl2br($profile->creating)), 'youtube') !!}
	<br><br>

	<h4>@lang( 'profile.category' )</h4>
	<a href="{{ route('browseCreators', ['category' => $profile->category->id, 'category_name' => $profile->category->category]) }}">
		<i class="fas fa-tags"></i> {{ $profile->category->category }}
	</a>
	<br>

	@if( $profile->hasSocialProfiles() )

	<h4>@lang( 'profile.socialProfiles' )</h4>

	<h5>
		@if( !is_null( $profile->fbUrl ) && !empty( $profile->fbUrl ) )
		<a href="{{ $profile->fbUrl }}" target="_blank" rel="nofollow" class="noHover">
			<i class="fab fa-facebook-f mr-2"></i>
		</a>
		@endif

		@if( !is_null( $profile->instaUrl ) && !empty( $profile->instaUrl ) )
		<a href="{{ $profile->instaUrl }}" target="_blank" rel="nofollow" class="noHover">
			<i class="fab fa-instagram mr-2 text-danger"></i>
		</a>
		@endif

		@if( !is_null( $profile->twUrl ) && !empty( $profile->twUrl ) )
		<a href="{{ $profile->twUrl }}" target="_blank" rel="nofollow" class="noHover text-info">
			<i class="fab fa-twitter mr-2"></i>
		</a>
		@endif

		@if( !is_null( $profile->ytUrl ) && !empty( $profile->ytUrl ) )
		<a href="{{ $profile->ytUrl }}" target="_blank" rel="nofollow" class="noHover text-danger">
			<i class="fab fa-youtube mr-2"></i>
		</a>
		@endif

		@if( !is_null( $profile->twitchUrl ) && !empty( $profile->twitchUrl ) )
		<a href="{{ $profile->twitchUrl }}" target="_blank" rel="nofollow" class="noHover">
			<i class="fab fa-twitch"></i>
		</a>
		@endif
	</h5>

	@endif

</div>

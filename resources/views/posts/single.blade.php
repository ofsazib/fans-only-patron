
@if($post->pinned == 'yes')
<div class="pt-4 pb-2 rounded text-right">
	<h5 class="text-primary"><i class="fa fa-bookmark"></i> @lang('v22.pinnedPost')</h5>
</div>
@endif
<div class="card mb-4 @if($post->pinned == 'yes') border-pinned @endif" data-post-id="{{ $post->id }}" >
	
	<div class="row px-4 pt-4 pb-1">

		<div class="col-3 col-sm-3 col-md-3 col-lg-2">
			<div class="profilePicSmall mt-0 ml-0 mr-2 mb-2">
				<a href="{{ $post->profile->url }}">
					<img src="{{ secure_image($post->profile->profilePic, 95, 95) }}" alt="" class="img-fluid">
				</a>
			</div>
		</div>
		
		<div class="col-9 col-sm-9 col-md-9 col-lg-10">
			<div class="mt-1 clearfix">

			<div class="float-left">
				<span class="post-username @if($post->profile->isVerified == 'Yes') post-user-badge @endif">{{ $post->profile->name }}</span>
				<br>
				<a href="{{ $post->profile->url }}" class="post-handle-url">
					{{ $post->profile->handle }}
				</a>
				<br>
				<span class="d-block d-sm-block d-md-none">
					<span class="text-muted">
					<small>
						<i class="fas fa-clock mr-1"></i> {{ $post->created_at->diffForHumans( ) }}
					</small>
				</span>
			</div>

			<div class="float-right ">

				<div class="float-left pt-2 d-none d-sm-none d-md-block">
					<span class="text-muted">
					<small>
						<i class="fas fa-clock mr-1"></i> {{ $post->created_at->diffForHumans( ) }}
					</small>
				</div>

				<div class="dropdown dropleft float-right">
					<a href="" class="btn text-secondary dropdown-toggle postActionsDropdown" data-toggle="dropdown" id="dropdown-{{ $post->id }}" aria-haspopup="true" aria-expanded="false">
						<h2 class="d-inline m-0 p-0"><i class="fas fa-ellipsis-h"></i></h2>
					</a>
					<div class="dropdown-menu" aria-labelledby="dropdown-{{ $post->id }}">
						@if( $post->isCreator() )
							@if($post->pinned == 'no')
								<a class="dropdown-item" href="{{  route('pinPost', ['post' => $post->id]) }}">@lang('v22.pinPost')</a>
							@else
								<a class="dropdown-item" href="{{  route('removePin', ['post' => $post->id]) }}">@lang('v22.removePin')</a>
							@endif
							<a class="dropdown-item" href="{{  route('editPost', ['post' => $post->id]) }}">@lang('post.editPost')</a>
							<a class="dropdown-item delete-post" href="{{ route('deletePost', ['post' => $post]) }}" data-id="{{  $post->id }}">@lang('post.deletePost')</a>
						@endif
						<a class="dropdown-item copyLink" href="javascript:void(0);" data-clipboard-text="{{ $post->slug }}">
							@lang( 'post.copyLink' )
						</a>
						<a class="dropdown-item" href="{{ $post->slug }}">
							@lang( 'post.postLink' )
						</a>
					</div>
				</div>
				
			</span>


			</div>
			</div>
		</div>

	</div>

@if( $post->userHasAccess() ) 
	
	<div class="pl-4 pr-4 pt-0 pb-2 text-333">
		{!! clean(turnLinksIntoAtags(nl2br($post->text_content)), 'youtube') !!}
	</div>

	@include('posts.post-media', ['post' => $post])


	<div class="border-top px-3 py-3">
		<h4 class="d-inline">
			<a href="@if(auth()->check()) javascript:void(0); @else {{ route( 'login' ) }} @endif" class="text-danger noHover @if(auth()->check()) lovePost @endif @if( auth()->check() && auth()->user()->hasLiked( $post )) d-none @endif" data-id="{{ $post->id }}">
				<i class="far fa-heart"></i> <span class="post-likes-count" data-id="{{ $post->id }}">{{ $post->likes->count() }}</span>
			</a>
			<a href="javascript:void(0);" class="text-danger noHover unlovePost @if( (auth()->check() && !auth()->user()->hasLiked( $post )) OR !auth()->check() ) d-none @endif" data-id="{{ $post->id }}">
				<i class="fas fa-heart"></i> <span class="post-likes-count" data-id="{{ $post->id }}">{{ $post->likes->count() }}</span>
			</a>
		</h4>		
		&nbsp;&nbsp;

		<h4 class="d-inline">
		<a href="@if(auth()->check()) javascript:void(0); @else {{ route( 'login' ) }} @endif" class="text-secondary noHover @if(auth()->check()) loadComments @endif" data-id="{{ $post->id }}">
			<i class="far fa-comments"></i> <span class="post-comments-count" data-id="{{ $post->id }}">{{ $post->comments->count() }}</span>
		</a>
		</h4>
		&nbsp;&nbsp;

		@include('tips.tip-form')

		<div class="leave-comment mt-3 mb-2 d-none" data-id="{{ $post->id }}">

			<input type="text" name="new-comment-{{ $post->id }}" class="form-control leave-comment-inp" placeholder="@lang('post.writeCommentAndPressEnter')" required="required" data-id="{{ $post->id }}">

		</div>

		<div class="post-{{ $post->id }}-lastId d-none"></div>

		<div class="appendComments d-none" data-id="{{ $post->id }}"></div>

		@if( $post->comments->count() > opt( 'commentsPerPost', 5 ) )
		<a class="loadMoreComments d-none" href="javascript:void(0);" data-id="{{ $post->id }}">
			@lang( 'post.loadMoreComments' )
		</a>

		<div class="noMoreComments d-none text-secondary" data-id="{{ $post->id }}">
			<i class="fas fa-exclamation-triangle"></i> @lang( 'post.noMoreComments' )
		</div>
		@endif

	</div>

@else
	{{-- if Post is Paid but current auth() user is not subscribed --}}

	<div class="pt-1 pb-2 pl-3">
		@if($post->media_type != 'None')
			{!! clean($post->text_content) !!}
		@endif
	</div>

	<div class="locked-post p-5 text-center text-secondary">
		<br><br>
	
		<h1 class="display-2">
		@if($post->media_type == 'None')
			<i class="fas fa-align-left"></i>
		@elseif($post->media_type == 'Image')
			<i class="fas fa-image"></i>
		@elseif($post->media_type == 'Video')
			<i class="fas fa-video"></i>
		@elseif($post->media_type == 'Audio')
			<i class="fas fa-music"></i>
		@elseif($post->media_type == 'ZIP')
			<i class="fas fa-file-archive"></i>
		@endif
		</h1>

		<i class="fa fa-lock"></i> @lang('post.locked')

		<br><br><br>
	</div>

	@if($post->profile->monthlyFee && $post->profile->minTip)
	<div class="ml-2 p-2">
		@include('tips.tip-form')
	</div>
	@endif

@endif

</div>

<style>
.embed-container {
  --video--width: 1296;
  --video--height: 540;

  position: relative;
  padding-bottom: calc(var(--video--height) / var(--video--width) * 100%); /* 41.66666667% */
  overflow: hidden;
  max-width: 100%;
  background: black;
}

.embed-container iframe,
.embed-container object,
.embed-container embed {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}
.text-purple {
	color: #b94a99;
}
.border-pinned {
	border: 1px solid #6066e2;
}
</style>
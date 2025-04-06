@foreach( $comments as $comment )

<div class="row singleComment" data-id="{{ $comment->id }}">
<div class="d-none d-sm-block d-md-block col-1">
	<div class="profilePicXS mt-0 ml-0 mr-2 mb-2 ml-md-3 shadow-sm">
		<a href="{{ route('profile.show', ['username' => $comment->commentator->profile->username ]) }}">
			<img src="{{ $comment->commentator->profile->profilePicture }}" alt="" width="40" height="40">
		</a>
	</div>
</div>
<div class="col-sm-11 col-md-11 col-12">
	<div class="comment-item p-2 mb-2 ml-1">
	<a href="{{ route('profile.show', ['username' => $comment->commentator->profile->username ]) }}" class="comment-user">
		{{ $comment->commentator->name }}
	</a> <small class="text-secondary">{{ $comment->created_at->diffForHumans() }}</small><br>

	<div class="text-wrap comment-content mt-1" data-id="{{  $comment->id }}" data-post="{{  $comment->commentable->id }}">
		{{ $comment->comment }}
	</div>
	<div class="comment-form" data-id="{{  $comment->id }}" data-post="{{  $comment->commentable->id }}"></div>

	@if( auth()->check() AND auth()->id() == $comment->user_id )
	<div class="comment-actions mt-2 border-top pt-2 text-secondary">
	<a href="javascript:void(0)" class="edit-comment text-secondary" data-id="{{ $comment->id }}" data-post="{{  $comment->commentable->id }}">
		<small><i class="fas fa-pencil-alt"></i> @lang('post.edit-comment')</small>
	</a>
	&nbsp;&nbsp;&nbsp;
	@endif
	@if( auth()->check() AND auth()->id() == $comment->user_id OR $comment->commentable->user_id == auth()->id() )
	<a href="javascript:void(0)" class="delete-comment text-secondary" data-id="{{ $comment->id }}" data-post="{{  $comment->commentable->id }}">
		<small><i class="fas fa-ban"></i> @lang('post.delete-comment')</small>
	</a>
	@endif
	</div>
	</div>
</div>
</div>

@endforeach
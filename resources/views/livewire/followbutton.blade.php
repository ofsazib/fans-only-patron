<div>
    <a href="javascript:void(0);" class="btn btn-secondary btn-sm" wire:click="toggleFollow">
	<i class="fas fa-hand-sparkles mr-1"></i> <span class="follow-text">
	@if( auth()->check() && !auth()->user()->isFollowing( $this->profile->user->id ) )
		 @lang( 'profile.subscribe' )
	@elseif( auth()->check() && auth()->user()->isFollowing( $this->profile->user->id ) )
		 @lang( 'profile.unsubscribe' )
	@else
		@lang( 'profile.subscribe' )
	@endif
	</span>
	</a>
	<div wire:loading wire:target="toggleFollow">
        <i class="fas fa-spinner fa-spin"></i> @lang( 'profile.pleaseWait' )
    </div>
</div>

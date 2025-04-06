<div>

	<ul class="nav nav-tabs mb-3">
	<li class="nav-item">
		<a class="nav-link @if($tab == 'Free') active @endif" href="javascript:void(0);" wire:click="tab('Free')">
			@lang('general.freeSubscriptions')
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link @if($tab == 'Paid') active @endif" href="javascript:void(0);" wire:click="tab('Paid')">
			@lang('general.paidSubscriptions')
		</a>
	</li>
	</ul>

@if( $subscribers->count() )

	<div class="row">

	@if($tab == 'Paid')

		@foreach($subscribers as $s)

		<div class="col-6 col-sm-2 mb-3">
			<div class="profilePicSmall mt-0 ml-0 mr-2 mb-2 @if($s->creator->isOnline()) profilePicOnlineSm @else profilePicOfflineSm @endif shadow">
			<a href="{{ $s->creator->profile->url }}">
				<img src="{{ secure_image($s->creator->profile->profilePic, 75, 75) }}" alt="" class="img-fluid">
			</a>
			</div>
		</div>
		<div class="col-6 col-sm-4 mb-3 profileFollowerList">
			{{ $s->creator->profile->name }}<br>
			<a href="{{ $s->creator->profile->url }}">{{ $s->creator->profile->handle }}</a>
			<br>
			<small>
				<em class="badge badge-secondary">
					@lang('general.expires') {{ $s->subscription_expires->diffForHumans() }}
				</em><br>

				@if($s->status == 'Active')

					@if($s->gateway != 'Admin' && $s->gateway != 'TransBank')
					<a href="javascript:void(0);" wire:click="confirmCancellation({{$s->id}})" class='text-danger'>
						@lang('general.cancelSubscription')
					</a>
					@endif

				@else
					@lang('general.subscriptionCanceled')
				@endif
			</small>
		</div>

		@endforeach

	@elseif($tab == 'Free')

		@foreach( $subscribers as $s)

		<div class="col-6 col-sm-2 mb-3">
			<div class="profilePicSmall mt-0 ml-0 mr-2 mb-2 @if($s->isOnline()) profilePicOnlineSm @else profilePicOfflineSm @endif shadow">
			<a href="{{ $s->profile->url }}">
				<img src="{{ secure_image($s->profile->profilePic, 75, 75) }}" alt="" class="img-fluid">
			</a>
			</div>
		</div>
		<div class="col-6 col-sm-4 mb-3 profileFollowerList">
			{{ $s->profile->name }}<br>
			<a href="{{ $s->profile->url }}">{{ $s->profile->handle }}</a>
			<br>
			<small>
				<a href="javascript:void(0);" wire:click="unfollow({{$s->id}})" class='text-danger'>
					@lang('general.unfollow')
				</a>
			</small>
		</div>

		<br>
		@endforeach

	@endif
	

	{{ $subscribers->links() }}

	</div>
	@else
		<h3 class="text-secondary text-center"><i class="far fa-surprise"></i> @lang( 'profile.noSubscriptions' )</h3>
	@endif


</div>
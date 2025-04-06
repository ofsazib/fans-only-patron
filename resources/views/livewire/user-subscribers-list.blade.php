<div>

	<ul class="nav nav-tabs mb-3">
	<li class="nav-item">
		<a class="nav-link @if($tab == 'Free') active @endif" href="javascript:void(0);" wire:click="tab('Free')">
			@lang('general.freeSubscribers')
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link @if($tab == 'Paid') active @endif" href="javascript:void(0);" wire:click="tab('Paid')">
			@lang('general.paidSubscribers')
		</a>
	</li>
	</ul>

@if( $subscribers->count() )

	<div class="row">

	@if($tab == 'Paid')

		@foreach($subscribers as $s)

		<div class="col-6 col-sm-2 mb-3">
			<div class="profilePicSmall mt-0 ml-0 mr-2 mb-2 @if($s->subscriber->isOnline()) profilePicOnlineSm @else profilePicOfflineSm @endif shadow">
			<a href="{{ $s->subscriber->profile->url }}">
				<img src="{{ secure_image($s->subscriber->profile->profilePic, 75, 75) }}" alt="" class="img-fluid">
			</a>
			</div>
		</div>
		<div class="col-6 col-sm-4 mb-3 profileFollowerList">
			{{ $s->subscriber->profile->name }}<br>
			<a href="{{ $s->subscriber->profile->url }}">{{ $s->subscriber->profile->handle }}</a>
			<br>
			<small>
				<em class="badge badge-secondary">
					@lang('general.expires') {{ $s->subscription_expires->diffForHumans() }}
				</em><br>
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
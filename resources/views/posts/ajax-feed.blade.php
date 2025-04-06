@if( $feed->count() )

	@foreach( $feed as $post )
		@component( 'posts.single', [ 'post' => $post ] ) @endcomponent
	@endforeach

@else

	<div class="card shadow p-4 mb-4 text-center">
		<h3 class="text-secondary">
			<i class="fas fa-comment-slash"></i> @lang( 'post.noMorePosts' )
		</h3>
	</div>

@endif
@extends( 'welcome' )

@section('seo_title') @lang('navigation.feed') - @endsection

@section( 'content' )
<div class="white-smoke-bg pt-4 pb-3">
<div class="container add-padding">
<div class="row">

@include('posts.sidebar-mobile')

<div class="col-12 col-md-7">

@if( auth()->check() )
	@include('posts.create-post', [ 'user' => auth()->user(), 'profile' => auth()->user()->profile ])
@endif

@if( $feed->count() )
	
<div class="postsList">
	@foreach( $feed as $post )
		@component( 'posts.single', [ 'post' => $post ] ) @endcomponent
	@endforeach
</div>

<div class="text-center loadingPostsMsg d-none">
  <h3 class="text-secondary"><i class="fas fa-spinner fa-spin mr-2"></i> @lang( 'post.isLoading' )</h3>
</div>

<div class="text-center noMorePostsMsg d-none">
	<div class="card p-3">
		<h3 class="text-secondary">@lang( 'post.noMorePosts' )</h3>
	</div>
</div>

@else

	<div class="card p-4 mb-4 text-center">
		<h5 class="text-secondary">
			<i class="fas fa-comment-slash"></i> @lang( 'post.noSubscriptions' )
		</h5>
	</div>

@endif
</div>

@include('posts.sidebar-desktop')

</div><!-- paddin top 5-->
</div><!-- ./container -->
</div><!-- .swhite-smoke -->
@endsection

{{-- attention, this is dynamically appended using stack() and push() functions of BLADE --}}
@push( 'extraJS' )
<script>
	$( function() {

		@if( auth()->check() )
		// auto expand textarea
		document.getElementById('createPost').addEventListener('keyup', function() {
		    this.style.overflow = 'hidden';
		    this.style.height = 0;
		    this.style.height = this.scrollHeight + 'px';
		}, false);
		@endif
		
		$(document.body).on('touchmove', onScroll); // for mobile
		$(window).on('scroll', onScroll);

		function onScroll() {

			if($(window).scrollTop() + $(window).height() >= $(document).height()) {

				// show spinner
				$( '.loadingPostsMsg' ).removeClass( 'd-none' );

				var lastId = $( '.lastId' ).val();

				$.getJSON( '{{ route( 'loadMorePosts', [ 'lastId' => '/' ]) }}/' + lastId, function( resp ) {

					if( resp.lastId != 0 ) {

						// append html
						$( '.postsList' ).append( resp.view );
						$('.lastId').val(resp.lastId);

					}else{

						// cancel scroll event
						$(window).off('scroll');

						$( '.noMorePostsMsg' ).removeClass( 'd-none' );

						$('.lastId').val(0);
					}

					$( '.loadingPostsMsg' ).addClass( 'd-none' );

					window.livewire.rescan();

				});
			}
		}
	});
</script>
@endpush
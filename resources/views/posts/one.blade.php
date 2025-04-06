@extends( 'welcome' )

@section('seo_title') #{{ $post->id }} - @endsection

@section( 'content' )
<div class="white-smoke-bg pt-4 pb-3">
<div class="container add-padding">
<div class="row">

@include('posts.sidebar-mobile')

<div class="col-12 col-md-7">

<div class="postsList">
	@component( 'posts.single', [ 'post' => $post ] ) @endcomponent
</div>

</div>

@include('posts.sidebar-desktop')

</div><!-- paddin top 5-->
</div><!-- ./container -->
</div><!-- .swhite-smoke -->
@endsection

@push('extraJS')
@if(auth()->check())
<script>
$(function(){
	$('.loadComments').trigger('click');
});
</script>
@endif
@endpush
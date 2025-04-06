@extends( 'welcome' )

@section('seo_title') @lang('navigation.editPost') - @endsection

@section( 'content' )
	<div class="white-smoke-bg pt-4 pb-3">
		<div class="container add-padding">
			<div class="row">
				<div class="col-12 col-md-8">

					{{-- source moved into /resources/vueapp/UpdatePost.vue --}}
					<div id="vue-update-post"></div>

				</div>
			</div>
		</div>
	</div>
@endsection

@push( 'extraJS' )
	<script src="{{ asset('resources/vueapp/dist/vuejs-bundle-v2.1.js') }}"></script>
@endpush

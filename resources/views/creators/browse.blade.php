@extends( 'welcome' )

@section('seo_title') @lang('homepage.browseCreators') - @endsection

@section( 'content' )
<div class="white-smoke-bg">
<br/>

<div class="container pt-3 pb-5">
    <h3 class="mb-4 text-center">@lang('homepage.browseCreators')</h3>
        @livewire('browse-creators', ['category' => $category])
    </div>
</div>


</div>
@endsection

@push('extraCSS')
<link href="{{ asset('css/bootstrap-select.min.css') }}" rel="stylesheet">
@endpush

@push('extraJS')
<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
@endpush
@extends('welcome')

@section('seo_title') {{ $page->page_title }} - @endsection

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-8 offset-0 offset-sm-0 offset-md-2">
            <div class="card shadow p-3">
            <h3>{{ $page->page_title }}</h3>
            <hr>

            {!! $page->page_content !!}
            </div>
        </div>
    </div>
</div>
@endsection
@extends('welcome')

@section('seo_title') @lang('navigation.messages') - @endsection

@section('content')

<div class="white-smoke-bg pt-4 pb-3">
    <div class="container no-padding">

        {{-- @livewire('message') --}}
        {{-- @include('livewire.message-in') --}}

        {{-- this is now based on VueJS --}}
        {{-- Sources are in /resources/vueapp/Messages.vue --}}
        <div id="vue-messages-app"></div>

    </div>
</div>

@endsection

{{-- attention, this is dynamically appended using stack() and push() functions of BLADE --}}
@push('extraJS')
<script src="{{ asset('resources/vueapp/dist/vuejs-bundle-v2.2.js') }}"></script>
@endpush

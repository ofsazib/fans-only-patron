

{{-- source located into /resources/vueapp/CreatePost.vue --}}
<div id="vue-create-post"></div>

@push('extraJS')
    <script src="{{ asset('resources/vueapp/dist/vuejs-bundle-v2.1.js') }}"></script>
@endpush

@push('extraCSS')
    <style>
        .MultiFile-preview {
            border-radius: 6px;
            display: block;
        }

        .MultiFile-remove, .MultiFile-title {
            display: none;
        }
    </style>
@endpush
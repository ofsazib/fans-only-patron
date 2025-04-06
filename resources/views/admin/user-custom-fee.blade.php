@extends('admin.base')

@push('scripts')
<script src="{{ url('js/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript">
    tinymce.init({
    selector: '.textarea',
	plugins: 'image code link lists',
	images_upload_url: '/admin/cms/upload-image',
	toolbar: 'code | formatselect fontsizeselect | insertfile a11ycheck | numlist bullist | bold italic | forecolor backcolor | template codesample | alignleft aligncenter alignright alignjustify | bullist numlist | link image tinydrive',

});
</script>
@endpush

@section('section_title')
<strong>User Fee</strong>
<br />
<a href="/admin/users">Users Overview</a>
@endsection

@section('section_body')

<form method="POST" action="/admin/users/save-custom-fee/{{ $user->id }}">
    {{ csrf_field() }}

    <h4>
        This feature allows you to set a custom fee for a user.
        <br />
        The standard fee for everyone is {{ opt('payment-settings.site_fee') }}%
    </h4>
    <hr />

    <dl>
        <dt>Fee Percentage % (between 1 and 99)</dt>
        <dd><input type="number" min="1" max="99" name="fee" class="form-control" style="width: 160px"
                value="{{ $user->fee }}" required>
        </dd>
        <datagrid></datagrid>
        <dt>&nbsp;</dt>
        <dd><input type="submit" name="sb_page" class="btn btn-primary" value="Adjust Fee"></dd>
    </dl>

</form>

@endsection
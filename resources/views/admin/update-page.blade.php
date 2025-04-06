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
	<strong>Pages Manager - Page Update</strong>
	<br/>
	<a href="{{ route('admin-cms') }}">Pages Overview</a>
@endsection

@section('section_body')
	
	<form method="POST">
		{{ csrf_field() }}

		<dl>
		<dt>Page Title</dt>
		<dd><input type="text" name="page_title" class="form-control" value="{{ $p->page_title }}"></dd>
		<br>
		<dt>Page Content</dt>
		<dd><textarea name="page_content" class="textarea form-control" rows="20">{!! $p->page_content !!}</textarea></dd>
		<dt>&nbsp;</dt>
		<dd><input type="submit" name="sb_page" class="btn btn-primary" value="Save"></dd>
		</dl>

	</form>

@endsection
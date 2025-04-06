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
<strong>Pages Manager</strong>
@endsection

@section('section_body')
<table class="table dataTable">
<thead>
<tr>
<th>ID</th>
<th>Title</th>
<th>Updated At</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
@foreach( $pages as $p )
<tr>
<td>{{ $p->id }}</td>
<td><a href="{{ App\Page::slug($p) }}" target="_blank">{{ $p->page_title }}</a></td>
<td>{{ $p->updated_at }}</td>
<td>
	<a href="/admin/cms-edit/{{ $p->id }}"><i class="glyphicon glyphicon-edit"></i></a> 
	<a href="/admin/cms-delete/{{ $p->id }}" data-method="delete" data-confirm="Are you sure?"><i class="glyphicon glyphicon-remove"></i></a> 
</td>
</tr>
@endforeach
</tbody>
</table>

@endsection

@section('extra_bottom')
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="box">
<div class="box-header with-border"><strong>Create Page</strong></div>
<div class="box-body">
<form method="POST">
{{ csrf_field() }}
<dl>
<dt>Page Title</dt>
<dd><input type="text" name="page_title" class="form-control" required="required" value="{{ old('page_title') }}"></dd>
<br>
<dt>Page Content</dt>
<dd><textarea name="page_content" class="textarea form-control" rows="20">{!! old('page_content') !!}</textarea></dd>
<dt>&nbsp;</dt>
<dd><input type="submit" name="sb_page" class="btn btn-primary" value="Save"></dd>
</dl>
</form>
</div>
<div class="box-footer"></div>
</div>
@endsection
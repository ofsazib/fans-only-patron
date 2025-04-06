@extends('admin.base')

@section('section_title')
<strong>Add your own custom CSS / JS to be loaded on front-end</strong>
@endsection

@section('section_body')

<form method="POST" action="/admin/saveExtraCSSJS">
{{ csrf_field() }}
    
<div class="row">
    <div class="col-xs-12 col-md-4">
        <h4>Extra CSS <small>without {{ '<style>' }} tags</small></h4>
        <textarea name="admin_extra_CSS" class="form-control" rows="25">{{ opt('admin_extra_CSS') }}</textarea>
    </div>
    <div class="col-xs-12 col-md-4">
        <h4>Extra JS <small>without {{ '<script>' }} tags</small></h4>
        <textarea name="admin_extra_JS" class="form-control" rows="25">{{ opt('admin_extra_JS') }}</textarea>
    </div>
    <div class="col-xs-12 col-md-4">
        <h4>Raw JS <small>accepts {{ '<script>' }} tags (ie. google analytics)</small></h4>
        <textarea name="admin_raw_JS" class="form-control" rows="25">{{ opt('admin_raw_JS') }}</textarea>
    </div>
</div>
<br>
<div class="text-center">
    <input type="submit" name="sb_settings" value="Save Extra JS/CSS" class="btn btn-primary">	
</div>
</form>
@endsection
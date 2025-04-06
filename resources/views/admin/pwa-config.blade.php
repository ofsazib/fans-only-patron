@extends('admin.base')

@section('section_title')
<strong>Setup PWA Icons</strong>
@endsection

@section('section_body')

<div class="well">
    1. On iOS people will be able to use Safari and add your site to "home screen". Only with Safari, Apple will not let Google or other browsers use this feature.
    <br>
    2. On Android using Google Chrome your users will be able to add your site to "home screen" as well.
</div>

<div class="alert alert-danger">
    <h4>Important: PWA will only work if you are using HTTPS (SSL)</h4>
</div>

<form method="POST" action="/admin/pwa-store-config" enctype="multipart/form-data">
{{ csrf_field() }}

<div class="row">
    <div class="col-xs-12 col-md-4">
        <strong>PWA App ShortName (ie. FansApp)</strong>
        <input type="text" name="laravel_short_pwa" value="{{ opt('laravel_short_pwa', 'FansApp') }}" class="form-control"/>
    </div>
</div>
<br>

<div class="row">
    <div class="col-xs-12 col-md-4">
        <h4><strong>Add your <strong>PNG</strong> ICONS for the PWA</strong><br><a href="https://maskable.app/editor" target="_blank">https://maskable.app/editor</a></h4>

        <strong><a href="{{ asset(opt('pwa_72x72', 'images/icons/icon-72x72.png')) }}" target="_blank"><i class="fa fa-eye"></i></a> 72x72 Maskable Icon</strong><br>
        <input type="file" name="files[72x72]" accept="image/png,.png">
        <br>

        <strong><a href="{{ asset(opt('pwa_96x96', 'images/icons/icon-96x96.png')) }}" target="_blank"><i class="fa fa-eye"></i></a> 96x96 Icon</strong><br>
        <input type="file" name="files[96x96]" accept="image/png,.png">
        <br>

        <strong><a href="{{ asset(opt('pwa_128x128', 'images/icons/icon-128x128.png')) }}" target="_blank"><i class="fa fa-eye"></i></a> 128x128 Icon</strong><br>
        <input type="file" name="files[128x128]" accept="image/png,.png">
        <br>

        <strong><a href="{{ asset(opt('pwa_144x144', 'images/icons/icon-144x144.png')) }}" target="_blank"><i class="fa fa-eye"></i></a> 144x144 Icon</strong><br>
        <input type="file" name="files[144x144]" accept="image/png,.png">
        <br>

        <strong><a href="{{ asset(opt('pwa_152x152', 'images/icons/icon-152x152.png')) }}" target="_blank"><i class="fa fa-eye"></i></a> 152x152 Icon</strong><br>
        <input type="file" name="files[152x152]" accept="image/png,.png">
        <br>

        <strong><a href="{{ asset(opt('pwa_384x384', 'images/icons/icon-384x384.png')) }}" target="_blank"><i class="fa fa-eye"></i></a> 384x384 Icon</strong><br>
        <input type="file" name="files[384x384]" accept="image/png,.png">
        <br>

        <strong><a href="{{ asset(opt('pwa_512x512', 'images/icons/icon-512x512.png')) }}" target="_blank"><i class="fa fa-eye"></i></a> 512x512 Icon</strong><br>
        <input type="file" name="files[512x512]" accept="image/png,.png">
        <br>

    </div><!-- icons row -->

    <div class="col-xs-12 col-md-4">
        <h4><strong>Add your <strong>PNG</strong> SPLASH screens for the PWA</strong> <br><a href="https://appsco.pe/developer/splash-screens" target="_blank">https://appsco.pe/developer/splash-screens</a> </h4>
    
        <strong><a href="{{ asset(opt('pwa_640x1136', 'images/icons/splash-640x1136.png')) }}" target="_blank"><i class="fa fa-eye"></i></a> 640x1136 Icon</strong><br>
        <input type="file" name="files[640x1136]" accept="image/png,.png">
        <br>
    
        <strong><a href="{{ asset(opt('pwa_750x1334', 'images/icons/splash-750x1334.png')) }}" target="_blank"><i class="fa fa-eye"></i></a> 750 x 1334 </strong><br>
        <input type="file" name="files[750x1334]" accept="image/png,.png">
        <br>

        <strong><a href="{{ asset(opt('pwa_1125x2436', 'images/icons/splash-1125x2436.png')) }}" target="_blank"><i class="fa fa-eye"></i></a> 1125 x 2436px</strong><br>
        <input type="file" name="files[1125x2436]" accept="image/png,.png">
        <br>
    
        <strong><a href="{{ asset(opt('pwa_1242x2208', 'images/icons/splash-1242x2208.png')) }}" target="_blank"><i class="fa fa-eye"></i></a> 1242x2208 Icon</strong><br>
        <input type="file" name="files[1242x2208]" accept="image/png,.png">
        <br>
    
        <strong><a href="{{ asset(opt('pwa_1536x2048', 'images/icons/splash-1536x2048.png')) }}" target="_blank"><i class="fa fa-eye"></i></a> 1536x2048 Icon</strong><br>
        <input type="file" name="files[1536x2048]" accept="image/png,.png">
        <br>
    
        <strong><a href="{{ asset(opt('pwa_1668x2224', 'images/icons/splash-1668x2224.png')) }}" target="_blank"><i class="fa fa-eye"></i></a> 1668x2224 Icon</strong><br>
        <input type="file" name="files[1668x2224]" accept="image/png,.png">
        <br>
    
        <strong><a href="{{ asset(opt('pwa_2048x2732', 'images/icons/splash-2048x2732.png')) }}" target="_blank"><i class="fa fa-eye"></i></a> 2048x2732 Icon</strong><br>
        <input type="file" name="files[2048x2732]" accept="image/png,.png">
        <br>
    </div><!-- icons row -->
    
    </div><!-- ./row -->
    
    <hr>
    <div class="row">
        <div class="col-xs-12 col-md-4 col-xs-offset-0 col-md-offset-2">
            <input type="submit" name="sb" value="Upload Selected Icons / Splash Screens"  class="btn btn-primary"/>
        </div>
    </div>
</form>

@endsection
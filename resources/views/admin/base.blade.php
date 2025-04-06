<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="crivion">
  <meta name="token" content="{{ csrf_token() }}">

  <title>Admin Panel</title>

  <!-- Bootstrap core CSS -->
  <link href="{{ asset( 'resources/assets/bootstrap.min.css' ) }}" rel="stylesheet">
  <link href="{{ asset( 'resources/assets/font-awesome.min.css' ) }}" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="{{ asset('resources/assets/sweetalert.css') }}" rel="stylesheet">
  <link href="{{ asset('resources/assets/socialicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('resources/assets/select2.min.css') }}" rel="stylesheet">
  <link href="{{ asset('resources/assets/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
  <link href="{{ asset('resources/assets/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
  <link href="{{ asset('resources/assets/bootstrap-wysihtml5.css')}}" rel="stylesheet">
  <link href="{{ asset('resources/assets/admin.css')}}" rel="stylesheet">
  <link href="{{ asset('css/ekko-lightbox.css') }}" rel="stylesheet">

  @livewireStyles

  <script src="{{ asset( 'resources/assets/js/jquery.js' ) }}" type="text/javascript"></script>
  <script src="{{ asset( 'resources/assets/js/bootstrap.min.js' ) }}" type="text/javascript"></script>
  <script src="{{ asset('resources/assets/js/sweetalert.min.js') }}"></script>
  <script src="{{ asset('resources/assets/js/raphael-min.js') }}"></script>
  <script src="{{ asset('resources/assets/js/morris.min.js') }}"></script>
  <script src="{{ asset( 'resources/assets/js/select2.full.min.js' ) }}" type="text/javascript"></script>
  <script src="{{ asset('resources/assets/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('resources/assets/datatables/dataTables.bootstrap.min.js') }}"></script>
  <script src="{{ asset('resources/assets/js/jquery-ui.js') }}"></script>
  <script src="{{ asset('resources/assets/js/jscolor.js') }}"></script>
  <script src="{{ asset('js/ekko-lightbox.min.js') }}"></script>
  <script src="{{ asset('resources/assets/js/admin.js') }}"></script>

  @stack('scripts')
  @livewireScripts

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="{{ asset( 'resources/assets/js/html5shiv.min.js') }}"></script>
    <script src="{{ asset( 'resources/assets/js/respond.min.js') }}"></script>
  <![endif]-->

    
</head>
<body>   
<nav class="navbar navbar-inverse sidebar" role="navigation">
    <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/admin">Admin 
        @if(defined('FANSAPP_VERSION'))
        <small class="text-danger">v{{ FANSAPP_VERSION }}</small>
        @endif
      </a>
      <a href="{{ route('home') }}" target="_blank">View Frontend</a>
      <br><br>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li @if(isset($active) AND ($active == 'dashboard')) class="active" @endif>
  <a href="/admin"><i class="fa fa-link pull-right hidden-xs showopacity"></i> <span>Dashboard</span></a>
</li>
<li @if(isset($active) AND ($active == 'users')) class="active" @endif>
  <a href="/admin/users"><i class="fa pull-right hidden-xs showopacity fa-users"></i> <span>Users</span></a>
</li>
<li @if(isset($active) AND ($active == 'moderation')) class="active" @endif>
  <a href="/admin/moderation/Image"><i class="fa pull-right hidden-xs showopacity fa-list"></i> <span>Moderation</span></a>
</li>
<li @if(isset($active) AND ($active == 'subscriptions')) class="active" @endif>
  <a href="/admin/subscriptions"><i class="fa pull-right hidden-xs showopacity  fa-address-card-o
"></i> <span>Subscriptions</span></a>
</li>
<li @if(isset($active) AND ($active == 'tips')) class="active" @endif>
  <a href="/admin/tips"><i class="fa pull-right hidden-xs showopacity  fa-envelope-open
"></i> <span>Tips</span></a>
</li>
<li @if(isset($active) AND ($active == 'unlocks')) class="active" @endif>
  <a href="/admin/unlocks"><i class="fa pull-right hidden-xs showopacity  fa-lock
"></i> <span>Unlocks</span></a>
</li>
<li @if(isset($active) AND ($active == 'tx')) class="active" @endif>
  <a href="/admin/tx"><i class="fa pull-right hidden-xs showopacity  fa-credit-card-alt
"></i> <span>Transactions</span></a>
</li>
<li @if(isset($active) AND ($active == 'profile-verifications')) class="active" @endif>
  <a href="/admin/profile-verifications"><i class="fa pull-right hidden-xs showopacity fa-globe"></i> <span>Verification Requests</span></a>
</li>
<li @if(isset($active) AND ($active == 'payment-requests')) class="active" @endif>
  <a href="/admin/payment-requests"><i class="fa pull-right hidden-xs showopacity fa-money"></i> <span>Payment Requests</span></a>
</li>
<li @if(isset($active) AND ($active == 'categories')) class="active" @endif>
  <a href="/admin/categories"><i class="fa pull-right hidden-xs showopacity fa-bars"></i> <span>Categories</span></a>
</li>
  <li @if(isset($active) AND ($active == 'pages')) class="active" @endif>
    <a href="/admin/cms"><i class="fa pull-right hidden-xs showopacity fa-sticky-note-o"></i> <span>Pages</span></a>
  </li>
  <li @if(isset($active) AND ($active == 'config')) class="active" @endif>
    <a href="/admin/configuration"><i class="fa pull-right hidden-xs showopacity fa-cog"></i> <span>Configuration</span></a>
  </li>
  <li @if(isset($active) AND ($active == 'cssjs')) class="active" @endif>
    <a href="/admin/cssjs"><i class="fa pull-right hidden-xs showopacity fa-css3"></i> <span>Extra CSS/JS</span></a>
  </li>
  <li @if(isset($active) AND ($active == 'payments')) class="active" @endif>
  <a href="/admin/payments-settings"><i class="fa pull-right hidden-xs showopacity fa-bank"></i> <span>Payments Settings</span></a>
</li>
  <li @if(isset($active) AND ($active == 'mail')) class="active" @endif>
  <a href="/admin/mailconfiguration"><i class="fa pull-right hidden-xs showopacity fa-at"></i> <span>Mail Server</span></a>
</li>
<li @if(isset($active) AND ($active == 'popup')) class="active" @endif>
  <a href="/admin/entry-popup"><i class="fa pull-right hidden-xs showopacity fa-at"></i> <span>Entry Popup</span></a>
</li>
<li @if(isset($active) AND ($active == 'cloud')) class="active" @endif>
  <a href="/admin/cloud"><i class="fa pull-right hidden-xs showopacity fa-cloud"></i> <span>Cloud Storage</span></a>
</li>
<li @if(isset($active) AND ($active == 'pwa-config')) class="active" @endif>
  <a href="/admin/pwa-config"><i class="fa pull-right hidden-xs showopacity fa-mobile" style="font-size: 20px;"></i> <span>PWA Icons</span></a>
</li>
<li @if(isset($active) AND ($active == 'simulator-config')) class="active" @endif>
  <a href="/admin/simulator-config"><i class="fa pull-right hidden-xs showopacity fa-simplybuilt" style="font-size: 12px;"></i> <span>Simulator Config</span></a>
</li>
<li @if(isset($active) AND ($active == 'admin-login')) class="active" @endif>
    <a href="/admin/config-logins"><i class="fa pull-right hidden-xs showopacity fa-cog"></i> <span>Admin Logins</span></a>
  </li>
<li>
  <a href="/admin/logout"><i class="fa pull-right hidden-xs showopacity fa-power-off"></i> <span>Log Out</span></a>
</li>
      </ul>
    </div>
  </div>
</nav>
<div class="main">

@if( session('msg') )
<div class="row">
<div class="col-xs-12">
<div class="alert alert-info alert-dismissible">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
<h4><i class="icon fa fa-info"></i> Alert!</h4>
  {{ session('msg') }}
</div>
</div>
</div>
@endif

@if (count($errors) > 0)
<div class="alert alert-danger alert-dismissible">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@yield('extra_top') 

<div class="col-xs-12">
<div class="row">
<div class="box">
  <div class="box-header with-border">@yield('section_title', 'Section Title')</div>
  <div class="box-body">
  @yield('section_body', 'Body')
  </div>
  <div class="box-footer"></div>
</div>
</div>
</div>

@yield('extra_bottom') 
        
</div><!-- container fluid -->   

<script>
  $('body').on('click', '[data-toggle="lightbox"]', function (event) {
		event.preventDefault();
		$(this).ekkoLightbox();
	});
</script>
  </body>
</html>

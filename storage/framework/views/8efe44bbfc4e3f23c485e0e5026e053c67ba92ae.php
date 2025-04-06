<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="crivion">
  <meta name="token" content="<?php echo e(csrf_token()); ?>">

  <title>Admin Panel</title>

  <!-- Bootstrap core CSS -->
  <link href="<?php echo e(asset( 'resources/assets/bootstrap.min.css' )); ?>" rel="stylesheet">
  <link href="<?php echo e(asset( 'resources/assets/font-awesome.min.css' )); ?>" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="<?php echo e(asset('resources/assets/sweetalert.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('resources/assets/socialicons.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('resources/assets/select2.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('resources/assets/datatables/jquery.dataTables.min.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('resources/assets/datatables/dataTables.bootstrap.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('resources/assets/bootstrap-wysihtml5.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('resources/assets/admin.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('css/ekko-lightbox.css')); ?>" rel="stylesheet">

  <?php echo \Livewire\Livewire::styles(); ?>


  <script src="<?php echo e(asset( 'resources/assets/js/jquery.js' )); ?>" type="text/javascript"></script>
  <script src="<?php echo e(asset( 'resources/assets/js/bootstrap.min.js' )); ?>" type="text/javascript"></script>
  <script src="<?php echo e(asset('resources/assets/js/sweetalert.min.js')); ?>"></script>
  <script src="<?php echo e(asset('resources/assets/js/raphael-min.js')); ?>"></script>
  <script src="<?php echo e(asset('resources/assets/js/morris.min.js')); ?>"></script>
  <script src="<?php echo e(asset( 'resources/assets/js/select2.full.min.js' )); ?>" type="text/javascript"></script>
  <script src="<?php echo e(asset('resources/assets/datatables/jquery.dataTables.min.js')); ?>"></script>
  <script src="<?php echo e(asset('resources/assets/datatables/dataTables.bootstrap.min.js')); ?>"></script>
  <script src="<?php echo e(asset('resources/assets/js/jquery-ui.js')); ?>"></script>
  <script src="<?php echo e(asset('resources/assets/js/jscolor.js')); ?>"></script>
  <script src="<?php echo e(asset('js/ekko-lightbox.min.js')); ?>"></script>
  <script src="<?php echo e(asset('resources/assets/js/admin.js')); ?>"></script>

  <?php echo $__env->yieldPushContent('scripts'); ?>
  <?php echo \Livewire\Livewire::scripts(); ?>


  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="<?php echo e(asset( 'resources/assets/js/html5shiv.min.js')); ?>"></script>
    <script src="<?php echo e(asset( 'resources/assets/js/respond.min.js')); ?>"></script>
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
        <?php if(defined('FANSAPP_VERSION')): ?>
        <small class="text-danger">v<?php echo e(FANSAPP_VERSION); ?></small>
        <?php endif; ?>
      </a>
      <a href="<?php echo e(route('home')); ?>" target="_blank">View Frontend</a>
      <br><br>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li <?php if(isset($active) AND ($active == 'dashboard')): ?> class="active" <?php endif; ?>>
  <a href="/admin"><i class="fa fa-link pull-right hidden-xs showopacity"></i> <span>Dashboard</span></a>
</li>
<li <?php if(isset($active) AND ($active == 'users')): ?> class="active" <?php endif; ?>>
  <a href="/admin/users"><i class="fa pull-right hidden-xs showopacity fa-users"></i> <span>Users</span></a>
</li>
<li <?php if(isset($active) AND ($active == 'moderation')): ?> class="active" <?php endif; ?>>
  <a href="/admin/moderation/Image"><i class="fa pull-right hidden-xs showopacity fa-list"></i> <span>Moderation</span></a>
</li>
<li <?php if(isset($active) AND ($active == 'subscriptions')): ?> class="active" <?php endif; ?>>
  <a href="/admin/subscriptions"><i class="fa pull-right hidden-xs showopacity  fa-address-card-o
"></i> <span>Subscriptions</span></a>
</li>
<li <?php if(isset($active) AND ($active == 'tips')): ?> class="active" <?php endif; ?>>
  <a href="/admin/tips"><i class="fa pull-right hidden-xs showopacity  fa-envelope-open
"></i> <span>Tips</span></a>
</li>
<li <?php if(isset($active) AND ($active == 'unlocks')): ?> class="active" <?php endif; ?>>
  <a href="/admin/unlocks"><i class="fa pull-right hidden-xs showopacity  fa-lock
"></i> <span>Unlocks</span></a>
</li>
<li <?php if(isset($active) AND ($active == 'tx')): ?> class="active" <?php endif; ?>>
  <a href="/admin/tx"><i class="fa pull-right hidden-xs showopacity  fa-credit-card-alt
"></i> <span>Transactions</span></a>
</li>
<li <?php if(isset($active) AND ($active == 'profile-verifications')): ?> class="active" <?php endif; ?>>
  <a href="/admin/profile-verifications"><i class="fa pull-right hidden-xs showopacity fa-globe"></i> <span>Verification Requests</span></a>
</li>
<li <?php if(isset($active) AND ($active == 'payment-requests')): ?> class="active" <?php endif; ?>>
  <a href="/admin/payment-requests"><i class="fa pull-right hidden-xs showopacity fa-money"></i> <span>Payment Requests</span></a>
</li>
<li <?php if(isset($active) AND ($active == 'categories')): ?> class="active" <?php endif; ?>>
  <a href="/admin/categories"><i class="fa pull-right hidden-xs showopacity fa-bars"></i> <span>Categories</span></a>
</li>
  <li <?php if(isset($active) AND ($active == 'pages')): ?> class="active" <?php endif; ?>>
    <a href="/admin/cms"><i class="fa pull-right hidden-xs showopacity fa-sticky-note-o"></i> <span>Pages</span></a>
  </li>
  <li <?php if(isset($active) AND ($active == 'config')): ?> class="active" <?php endif; ?>>
    <a href="/admin/configuration"><i class="fa pull-right hidden-xs showopacity fa-cog"></i> <span>Configuration</span></a>
  </li>
  <li <?php if(isset($active) AND ($active == 'cssjs')): ?> class="active" <?php endif; ?>>
    <a href="/admin/cssjs"><i class="fa pull-right hidden-xs showopacity fa-css3"></i> <span>Extra CSS/JS</span></a>
  </li>
  <li <?php if(isset($active) AND ($active == 'payments')): ?> class="active" <?php endif; ?>>
  <a href="/admin/payments-settings"><i class="fa pull-right hidden-xs showopacity fa-bank"></i> <span>Payments Settings</span></a>
</li>
  <li <?php if(isset($active) AND ($active == 'mail')): ?> class="active" <?php endif; ?>>
  <a href="/admin/mailconfiguration"><i class="fa pull-right hidden-xs showopacity fa-at"></i> <span>Mail Server</span></a>
</li>
<li <?php if(isset($active) AND ($active == 'popup')): ?> class="active" <?php endif; ?>>
  <a href="/admin/entry-popup"><i class="fa pull-right hidden-xs showopacity fa-at"></i> <span>Entry Popup</span></a>
</li>
<li <?php if(isset($active) AND ($active == 'cloud')): ?> class="active" <?php endif; ?>>
  <a href="/admin/cloud"><i class="fa pull-right hidden-xs showopacity fa-cloud"></i> <span>Cloud Storage</span></a>
</li>
<li <?php if(isset($active) AND ($active == 'pwa-config')): ?> class="active" <?php endif; ?>>
  <a href="/admin/pwa-config"><i class="fa pull-right hidden-xs showopacity fa-mobile" style="font-size: 20px;"></i> <span>PWA Icons</span></a>
</li>
<li <?php if(isset($active) AND ($active == 'simulator-config')): ?> class="active" <?php endif; ?>>
  <a href="/admin/simulator-config"><i class="fa pull-right hidden-xs showopacity fa-simplybuilt" style="font-size: 12px;"></i> <span>Simulator Config</span></a>
</li>
<li <?php if(isset($active) AND ($active == 'admin-login')): ?> class="active" <?php endif; ?>>
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

<?php if( session('msg') ): ?>
<div class="row">
<div class="col-xs-12">
<div class="alert alert-info alert-dismissible">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
<h4><i class="icon fa fa-info"></i> Alert!</h4>
  <?php echo e(session('msg')); ?>

</div>
</div>
</div>
<?php endif; ?>

<?php if(count($errors) > 0): ?>
<div class="alert alert-danger alert-dismissible">
    <ul>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php endif; ?>

<?php echo $__env->yieldContent('extra_top'); ?> 

<div class="col-xs-12">
<div class="row">
<div class="box">
  <div class="box-header with-border"><?php echo $__env->yieldContent('section_title', 'Section Title'); ?></div>
  <div class="box-body">
  <?php echo $__env->yieldContent('section_body', 'Body'); ?>
  </div>
  <div class="box-footer"></div>
</div>
</div>
</div>

<?php echo $__env->yieldContent('extra_bottom'); ?> 
        
</div><!-- container fluid -->   

<script>
  $('body').on('click', '[data-toggle="lightbox"]', function (event) {
		event.preventDefault();
		$(this).ekkoLightbox();
	});
</script>
  </body>
</html>
<?php /**PATH /Users/crivion/Sites/patrons/resources/views/admin/base.blade.php ENDPATH**/ ?>
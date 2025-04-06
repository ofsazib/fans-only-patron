<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="crivion">
    <link rel="shortcut icon" type="image/png" href="<?php echo e(asset(opt('favicon', 'favicon.png'))); ?>" sizes="128x128" />
    <meta name="_token" content="<?php echo e(csrf_token()); ?>" />

    <title><?php echo $__env->yieldContent('seo_title', ''); ?> <?php echo e(opt( 'seo_title' )); ?></title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/bootstrap.min.css')); ?>">

    <!-- Growl CSS -->
    <link href="<?php echo e(asset('css/jquery.growl.css')); ?>" rel="stylesheet">

    <!-- FA CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/fa/css/all.min.css')); ?>">

    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/jquery-ui.min.css')); ?>" />

    <!-- Lightbox CSS -->
    <link href="<?php echo e(asset('css/ekko-lightbox.css')); ?>" rel="stylesheet">

    <!-- Cookie Consent -->
    <link href="<?php echo e(asset('css/cookieconsent.min.css')); ?>" rel="stylesheet">

    <!-- APP CSS -->
    <link href="<?php echo e(asset('css/app-v2x.css')); ?>" rel="stylesheet">

    <!-- Web Application Manifest -->
    <link rel="manifest" href="<?php echo e(route('pwa-manifest')); ?>">

    <!-- Chrome for Android theme color -->
    <meta name="theme-color" content="<?php echo e(config('pwa.manifest.theme_color')); ?>">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="<?php echo e(config('pwa.manifest.display') == 'standalone' ? 'yes' : 'no'); ?>">
    <meta name="application-name" content="<?php echo e(opt('laravel_short_pwa', 'FansApp')); ?>">
    <link rel="icon" sizes="512x512" href="/<?php echo e(cache()->has('pwa_512x512') ? cache()->get('pwa_512x512') : opt('pwa_512x512', config('pwa.manifest.icons.512x512.path'))); ?>">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="<?php echo e(config('pwa.manifest.display') == 'standalone' ? 'yes' : 'no'); ?>">
    <meta name="apple-mobile-web-app-status-bar-style" content="<?php echo e(config('pwa.manifest.status_bar')); ?>">
    <meta name="apple-mobile-web-app-title" content="<?php echo e(opt('laravel_short_pwa', 'FansApp')); ?>">
    <link rel="apple-touch-icon" href="/<?php echo e(cache()->has('pwa_512x512') ? cache()->get('pwa_512x512') : opt('pwa_512x512', config('pwa.manifest.icons.512x512.path'))); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@latest/pace-theme-default.min.css">

    
    <link href="/<?php echo e(cache()->has('pwa_72x72') ? cache()->get('pwa_72x72') : opt('pwa_72x72', config('pwa.manifest.splash.640x1136'))); ?>" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/<?php echo e(cache()->has('pwa_750x1334') ? cache()->get('pwa_750x1334') : opt('pwa_750x1334', config('pwa.manifest.splash.750x1334'))); ?>" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/<?php echo e(cache()->has('pwa_1242x2208') ? cache()->get('pwa_1242x2208') : opt('pwa_1242x2208', config('pwa.manifest.splash.1242x2208'))); ?>" media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
    <link href="/<?php echo e(cache()->has('pwa_1125x2436') ? cache()->get('pwa_1125x2436') : opt('pwa_1125x2436', config('pwa.manifest.splash.1125x2436'))); ?>" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
    <link href="/<?php echo e(cache()->has('pwa_1536x2048') ? cache()->get('pwa_1536x2048') : opt('pwa_1536x2048', config('pwa.manifest.splash.1536x2048'))); ?>" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/<?php echo e(cache()->has('pwa_1668x2224') ? cache()->get('pwa_1668x2224') : opt('pwa_1668x2224', config('pwa.manifest.splash.1668x2224'))); ?>" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/<?php echo e(cache()->has('pwa_2048x2732') ? cache()->get('pwa_2048x2732') : opt('pwa_2048x2732', config('pwa.manifest.splash.2048x2732'))); ?>" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />

    <!-- Tile for Win8 -->
    <meta name="msapplication-TileColor" content="<?php echo e(config('pwa.manifest.background_color')); ?>">
    <meta name="msapplication-TileImage" content="/<?php echo e(cache()->has('pwa_512x512') ? cache()->get('pwa_512x512') : opt('pwa_512x512', config('pwa.manifest.icons.512x512.path'))); ?>">
    

    <script type="text/javascript">
      // Initialize the service worker
      if ('serviceWorker' in navigator) {
          navigator.serviceWorker.register('/serviceworker.js', {
              scope: '.'
          }).then(function (registration) {
              // Registration was successful
              console.log('PWA: ServiceWorker registration successful with scope: ', registration.scope);
          }, function (err) {
              // registration failed :(
              console.log('PWA: ServiceWorker registration failed: ', err);
          });
      }
    </script>

    <?php echo \Livewire\Livewire::styles(); ?>


    <!-- Views CSS -->
    <?php echo $__env->yieldPushContent( 'extraCSS' ); ?>

    <!-- Custom CSS from admin panel -->
    <style>
      <?php if($leftGradient = opt('hgr_left') AND $rightGradient = opt('hgr_right') AND $fontColor = opt('header_fcolor')): ?>
      .banner_box, .innerheaders {
        background: <?php echo e($leftGradient); ?>;
        background: -webkit-linear-gradient(to left, <?php echo e($leftGradient); ?>, <?php echo e($rightGradient); ?>);
        background: linear-gradient(to left, <?php echo e($leftGradient); ?>, <?php echo e($rightGradient); ?>);
        color: <?php echo e($fontColor); ?>;
      }
      <?php endif; ?>
      <?php if($btnBg = opt('red_btn_bg') AND $btnFt = opt('red_btn_font')): ?>
      .banner_details a.explore_btn, .banner_details a.explore_btn:hover, .creator_sec a.browse_btn, .creator_sec a.browse_btn:hover {
          background: <?php echo e($btnBg); ?>;
          border: <?php echo e($btnBg); ?>;
          color: <?php echo e($btnFt); ?>;
          box-shadow: none;
      }
      .welcome_sec p b {
        color:<?php echo e($btnBg); ?>;
      }
      .welcome_sec p:before {
        background: <?php echo e($btnBg); ?>;
      }
      <?php endif; ?>
    </style>

    <?php if($extraCSS = opt('admin_extra_CSS')): ?>
    <style>
    <?php echo $extraCSS; ?>

    </style>
    <?php endif; ?>

    <?php if($extraRawJS = opt('admin_raw_JS')): ?>
    <?php echo $extraRawJS; ?>

    <?php endif; ?>

    <!-- Set JS variables -->
    <script>
    var currencySymbol = '<?php echo e(opt( 'payment-settings.currency_symbol' )); ?>';
    var currencyCode   = '<?php echo e(opt( 'payment-settings.currency_code' )); ?>';
    var platformFee    = <?php echo e(opt( 'payment-settings.site_fee' )); ?>;
    var pleaseWriteSomething = '<?php echo app('translator')->get('post.pleaseWriteSomething'); ?>';
    var loadPostById = '<?php echo e(route( 'loadPostById', [ 'post' => '/' ] )); ?>';
    var successfullyCopiedLink = '<?php echo app('translator')->get('post.successfullyCopiedLink'); ?>';
    var friendRequestURI = '<?php echo e(route( 'followUser', [ 'user' => '/' ] )); ?>';
    var loginURI = '<?php echo e(route( 'login')); ?>';
    var likeURI = '<?php echo e(route( 'likePost', [ 'post' => '/' ])); ?>';
    var commentsURI = '<?php echo e(route( 'loadCommentsForPost', [ 'post' => '/', 'lastId' => '/' ])); ?>';
    var postCommentURI = '<?php echo e(route('postComment', ['post'=>'/'])); ?>';
    var loadCommentByIdURI = '<?php echo e(route('loadCommentById', ['comment'=>'/', 'post' => '/'])); ?>';
    var deleteCommentURI = '<?php echo e(route('deleteComment', ['comment' => '/'])); ?>';
    var editCommentsURI = '<?php echo e(route('editComment', ['comment' => '/'])); ?>';
    var updateCommentsURI = '<?php echo e(route('updateComment')); ?>';
    var confirmButton = '<?php echo app('translator')->get('validation.confirm-button'); ?>';
    var cancelButton = '<?php echo app('translator')->get('validation.cancel-button'); ?>';
    var confirmationTitle = '<?php echo app('translator')->get('validation.confirmation'); ?>';
    var confirmationMessage = '<?php echo app('translator')->get('validation.confirmation_message'); ?>';
    var successfullyRemovedComment = '<?php echo app('translator')->get('post.successfullyRemovedComment'); ?>';
    var successfullyRemovedPost =  '<?php echo app('translator')->get('post.successfullyRemovedPost'); ?>';
    var textNo = '<?php echo app('translator')->get('general.no'); ?>';
    var textYes = '<?php echo app('translator')->get('general.yes'); ?>';
    var cardGateway = '<?php echo e(opt('card_gateway', 'Stripe')); ?>';
    var card = '<?php echo app('translator')->get('general.creditCard'); ?>';
    var addCard = '<?php echo app('translator')->get('general.addCard'); ?>';
    var loginRoute = '<?php echo e(route('login')); ?>';
    var addCardRoute = '<?php echo e(route('billing.cards')); ?>';
    var paypalEnabled = '<?php echo e(opt('paypalEnable', 'No')); ?>';
    var paypalEmail = '<?php echo e(opt('paypal_email')); ?>';
    var search = '<?php echo app('translator')->get('v192.search'); ?>';
    var messages = '<?php echo app('translator')->get('messages.messages'); ?>';
    var unlockLinkTitle = '<?php echo app('translator')->get('v19.unlockLinkTitle'); ?>';
    var openConversation = '<?php echo app('translator')->get('v192.openConversation'); ?>';
    var enableMediaDl = '<?php echo e(opt('enableMediaDownload')); ?>';
    var zipDownload = '<?php echo app('translator')->get('v16.zipDownload'); ?>';
    var setPrice = '<?php echo app('translator')->get('v192.setPrice'); ?>';
    var setFree = '<?php echo app('translator')->get('v192.setFree'); ?>';
    var payWithCrypto = '<?php echo app('translator')->get('v192.crypto'); ?>';
    var savePost = '<?php echo app('translator')->get('post.savePost'); ?>';
    var writeSomething = '<?php echo app('translator')->get('post.writeSomething'); ?>';
    var updatePost = '<?php echo app('translator')->get('v192.update'); ?>';
    var imageUpload = '<?php echo app('translator')->get('post.imageUpload'); ?>';
    var videoUpload = '<?php echo app('translator')->get('post.videoUpload'); ?>';
    var audioUpload = '<?php echo app('translator')->get('post.audioUpload'); ?>';
    var removeMedia = '<?php echo app('translator')->get('post.removeMedia'); ?>';
    var zipUpload = '<?php echo app('translator')->get('v16.zipUpload'); ?>';
    var freePost = '<?php echo app('translator')->get('post.freePost'); ?>';
    var paidPost = '<?php echo app('translator')->get('post.paidPost'); ?>';
    var successfullyUpdatedPost = '<?php echo app('translator')->get('post.successfullyUpdatedPost'); ?>';
    var processingUpload = '<?php echo app('translator')->get('v192.processingUpload'); ?>';
    </script>

    <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>


  </head>
  <body>
  <div id="wrap">
  <div id="main">
  

    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
        <?php echo csrf_field(); ?>
    </form>

    <?php echo $__env->make( 'partials/topnavi' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <main role="main">
    <?php echo $__env->yieldContent( 'content' ); ?>
    </main>

    

  </div>
  </div>

    <?php echo $__env->make( 'partials/bottomnavi' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- jQuery Lib -->
    <script src="<?php echo e(asset('js/jquery.min.js')); ?>"></script>

    <!-- Popper JS -->
    <script src="<?php echo e(asset('js/popper.min.js')); ?>"></script>

    <!-- Twitter Bootstrap 4 Lib -->
    <script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>

    <!-- jQuery UI JS -->
    <script src="<?php echo e(asset('js/jquery-ui.min.js')); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

    <!-- FA JS -->
    <script src="<?php echo e(asset('css/fa/js/all.min.js')); ?>"></script>

    <!-- Clipboard JS -->
    <script src="<?php echo e(asset('js/clipboard.min.js')); ?>"></script>

    <!-- Growl JS -->
    <script src="<?php echo e(asset('js/jquery.growl.js')); ?>"></script>

    <!-- Ajax Form -->
    <script src="<?php echo e(asset('js/jquery.form.min.js')); ?>"></script>

    <!-- jquery jscroll -->
    <script src="<?php echo e(asset('js/jquery.jscroll.min.js')); ?>"></script>

    <!-- Lightbox JS -->
    <script src="<?php echo e(asset('js/ekko-lightbox.min.js')); ?>"></script>

    <?php echo \Livewire\Livewire::scripts(); ?>


    <!-- SweetAlert JS -->
    <script src="<?php echo e(asset('js/sweetalert.min.js')); ?>"></script>

    <!-- App JS -->
    <script src="<?php echo e(asset('js/app.js?v=') . microtime()); ?>"></script>

    <script src="<?php echo e(asset( 'js/cookieconsent.min.js' )); ?>"></script>

    
    <script>
        window.cookieconsent.initialise({
          "palette": {
            "popup": {
              "background": "#edeff5",
              "text": "#838391"
            },
            "button": {
              "background": "#4b81e8"
            }
          },
          "content": {
            "message": "<?php echo app('translator')->get('general.cookieMessage'); ?>",
            "dismiss": "<?php echo app('translator')->get('general.cookieDismiss'); ?>",
            "link": "<?php echo app('translator')->get('general.privacyPolicyText'); ?>",
            "href": "<?php echo app('translator')->get('v14.privacyPolicyLink'); ?>",
          }
        });

        $(document).on('contextmenu', 'img', function() {
            return false;
        });

        $( function() {
          $("video, audio, a[data-toggle=lightbox]").bind("contextmenu",function(){
            return false;
          });
        });
    </script>

    <?php echo $__env->make('sweet::alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if($errors->any()): ?>
    <script type="text/javascript">
        var errorList = '';
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            errorList += '<?php echo e($error); ?>. ';
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        swal({ title   : '', icon    : 'error', text : errorList });
        
    </script>
    <?php endif; ?>

    <!-- Entry Popup -->
    <?php if(opt('site_entry_popup', 'No') == 'Yes' AND !request()->cookie('entryConfirmed')): ?>
    <script>
    swal({
      title: '<?php echo e(opt('entry_popup_title', 'Entry popup title')); ?>',
      text: '<?php echo e(opt('entry_popup_message', 'Entry popup message')); ?>',
      icon: "info",
      buttons: true,
      dangerMode: true,
      buttons: ['<?php echo e(opt('entry_popup_cancel_text', 'Cancel')); ?>', '<?php echo e(opt('entry_popup_confirm_text', 'Continue')); ?>'],
    })
    .then((isConfirmed) => {
        if (isConfirmed) {
          $.get('<?php echo e(route('entryPopupCookie')); ?>', function(resp) {
            document.location.href = document.location.href;
          });
        } else {
          return window.location.href= "<?php echo e(opt('entry_popup_awayurl', 'https://google.com')); ?>";
        }
    });
    </script>
    <?php endif; ?>

    <!-- Extra JS -->
    <?php echo $__env->yieldPushContent( 'extraJS' ); ?>

    <!-- Extra JS FROM Admin Panel -->
    <?php if($extraJS = opt('admin_extra_JS')): ?>
    <script>
    <?php echo $extraJS; ?>

    </script>
    <?php endif; ?>

  </body>
</html><?php /**PATH /Users/crivion/Sites/patrons/resources/views/welcome.blade.php ENDPATH**/ ?>
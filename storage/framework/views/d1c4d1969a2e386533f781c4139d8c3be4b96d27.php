<header class="header_sec innerheaders">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light nav_top">

            <a class="navbar-brand" href="<?php echo e(route('home')); ?>">
                <?php if($logo = opt('site_logo')): ?>
                    <img src="<?php echo e(asset($logo)); ?>" alt="logo" class="site-logo"/>
                <?php else: ?>
                    <?php echo e(opt( 'site_title' )); ?>

                <?php endif; ?>
            </a><!-- logo -->

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars text-white"></i>
            </button><!-- navbar toggler icon (mobile) -->

            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">

                <?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('search-creators', ['search_type' => 'mobile'])->dom;
} elseif ($_instance->childHasBeenRendered('dJ8puzJ')) {
    $componentId = $_instance->getRenderedChildComponentId('dJ8puzJ');
    $componentTag = $_instance->getRenderedChildComponentTagName('dJ8puzJ');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('dJ8puzJ');
} else {
    $response = \Livewire\Livewire::mount('search-creators', ['search_type' => 'mobile']);
    $dom = $response->dom;
    $_instance->logRenderedChild('dJ8puzJ', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>

                <button class="navbar-toggler close_tgl" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <img src="<?php echo e(asset('images/close.png')); ?>" alt=""/>
                </button><!-- close navi on mobile -->

                <ul class="navbar-nav menu_sec">
                    <?php if( auth()->guest() ): ?>
                        <li>
                            <a href="/"><?php echo app('translator')->get( 'navigation.home' ); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if( !auth()->guest() ): ?>
                        <li>
                            <a href="<?php echo e(route('feed')); ?>"><?php echo app('translator')->get('navigation.feed'); ?></a>
                        </li>
                        <li>
                            <?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('notifications-icon')->dom;
} elseif ($_instance->childHasBeenRendered('4Rwqlme')) {
    $componentId = $_instance->getRenderedChildComponentId('4Rwqlme');
    $componentTag = $_instance->getRenderedChildComponentTagName('4Rwqlme');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('4Rwqlme');
} else {
    $response = \Livewire\Livewire::mount('notifications-icon');
    $dom = $response->dom;
    $_instance->logRenderedChild('4Rwqlme', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>
                        </li>
                        <li>
                            <?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('unread-messages-count')->dom;
} elseif ($_instance->childHasBeenRendered('CB67jlK')) {
    $componentId = $_instance->getRenderedChildComponentId('CB67jlK');
    $componentTag = $_instance->getRenderedChildComponentTagName('CB67jlK');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('CB67jlK');
} else {
    $response = \Livewire\Livewire::mount('unread-messages-count');
    $dom = $response->dom;
    $_instance->logRenderedChild('CB67jlK', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>
                        </li>
                        <li>
                            <a href="<?php echo e(route('profile.show', ['username' => auth()->user()->profile->username ])); ?>">
                                <?php echo app('translator')->get('navigation.myProfile'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('startMyPage')); ?>">
                                <?php echo app('translator')->get('navigation.account'); ?>
                                <?php if(auth()->user()->profile->isVerified == 'Yes' && auth()->user()->profile->monthlyFee): ?>
                                    <span class=""><?php echo e('(' . opt('payment-settings.currency_symbol') . number_format(auth()->user()->balance,2) . ')'); ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a href="<?php echo e(route('browseCreators')); ?>"><?php echo app('translator')->get('navigation.exploreCreators'); ?></a>
                    </li>
                    <?php if( auth()->guest() ): ?>
                        <li class="d-none d-sm-none d-md-block">
                            <a href="<?php echo e(route('register')); ?>"
                               class="border-white border-radius-account-buttons padding-account-buttons signupButton">
                                <i class="fas fa-user"></i> <?php echo app('translator')->get('navigation.signUp'); ?>
                            </a>
                        </li>
                        <li class="d-none d-sm-none d-md-block">
                            <a href="<?php echo e(route('login')); ?>"
                               class="bg-white border-radius-account-buttons padding-account-buttons loginButton">
                                <i class="fas fa-sign-in-alt"></i> <?php echo app('translator')->get('navigation.login'); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if( !auth()->guest() ): ?>
                        <li>
                            <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <?php echo app('translator')->get('navigation.logout'); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('search-creators', ['search_type' => 'desktop'])->dom;
} elseif ($_instance->childHasBeenRendered('2b99kA2')) {
    $componentId = $_instance->getRenderedChildComponentId('2b99kA2');
    $componentTag = $_instance->getRenderedChildComponentTagName('2b99kA2');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('2b99kA2');
} else {
    $response = \Livewire\Livewire::mount('search-creators', ['search_type' => 'desktop']);
    $dom = $response->dom;
    $_instance->logRenderedChild('2b99kA2', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>
                </ul>
                <ul class="rt_btn d-lg-none d-md-block d-sm-block d-block">
                    <?php if( auth()->guest() ): ?>
                        <li>
                            <a href="<?php echo e(route('register')); ?>"
                               class="border-black border-radius-account-buttons">
                                <i class="fas fa-user"></i> <?php echo app('translator')->get('navigation.signUp'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('login')); ?>"
                               class="border-black border-radius-account-buttons">
                                <i class="fas fa-sign-in-alt"></i> <?php echo app('translator')->get('navigation.login'); ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </div>
</header>
<?php /**PATH /var/www/works.crivion.com/resources/views/partials/topnavi.blade.php ENDPATH**/ ?>
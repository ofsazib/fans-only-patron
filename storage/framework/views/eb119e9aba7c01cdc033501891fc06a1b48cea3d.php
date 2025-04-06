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
} elseif ($_instance->childHasBeenRendered('ybJ2v1j')) {
    $componentId = $_instance->getRenderedChildComponentId('ybJ2v1j');
    $componentTag = $_instance->getRenderedChildComponentTagName('ybJ2v1j');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('ybJ2v1j');
} else {
    $response = \Livewire\Livewire::mount('search-creators', ['search_type' => 'mobile']);
    $dom = $response->dom;
    $_instance->logRenderedChild('ybJ2v1j', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
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
} elseif ($_instance->childHasBeenRendered('IYwzEfC')) {
    $componentId = $_instance->getRenderedChildComponentId('IYwzEfC');
    $componentTag = $_instance->getRenderedChildComponentTagName('IYwzEfC');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('IYwzEfC');
} else {
    $response = \Livewire\Livewire::mount('notifications-icon');
    $dom = $response->dom;
    $_instance->logRenderedChild('IYwzEfC', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>
                        </li>
                        <li>
                            <?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('unread-messages-count')->dom;
} elseif ($_instance->childHasBeenRendered('vejnYJJ')) {
    $componentId = $_instance->getRenderedChildComponentId('vejnYJJ');
    $componentTag = $_instance->getRenderedChildComponentTagName('vejnYJJ');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('vejnYJJ');
} else {
    $response = \Livewire\Livewire::mount('unread-messages-count');
    $dom = $response->dom;
    $_instance->logRenderedChild('vejnYJJ', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
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
} elseif ($_instance->childHasBeenRendered('z0KhD0h')) {
    $componentId = $_instance->getRenderedChildComponentId('z0KhD0h');
    $componentTag = $_instance->getRenderedChildComponentTagName('z0KhD0h');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('z0KhD0h');
} else {
    $response = \Livewire\Livewire::mount('search-creators', ['search_type' => 'desktop']);
    $dom = $response->dom;
    $_instance->logRenderedChild('z0KhD0h', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
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
<?php /**PATH /Users/crivion/Sites/patrons/resources/views/partials/topnavi.blade.php ENDPATH**/ ?>
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
} elseif ($_instance->childHasBeenRendered('G1zdF6e')) {
    $componentId = $_instance->getRenderedChildComponentId('G1zdF6e');
    $componentTag = $_instance->getRenderedChildComponentTagName('G1zdF6e');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('G1zdF6e');
} else {
    $response = \Livewire\Livewire::mount('search-creators', ['search_type' => 'mobile']);
    $dom = $response->dom;
    $_instance->logRenderedChild('G1zdF6e', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
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
} elseif ($_instance->childHasBeenRendered('yEqNC71')) {
    $componentId = $_instance->getRenderedChildComponentId('yEqNC71');
    $componentTag = $_instance->getRenderedChildComponentTagName('yEqNC71');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('yEqNC71');
} else {
    $response = \Livewire\Livewire::mount('notifications-icon');
    $dom = $response->dom;
    $_instance->logRenderedChild('yEqNC71', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
}
echo $dom;
?>
                        </li>
                        <li>
                            <?php
if (! isset($_instance)) {
    $dom = \Livewire\Livewire::mount('unread-messages-count')->dom;
} elseif ($_instance->childHasBeenRendered('xCwJqVo')) {
    $componentId = $_instance->getRenderedChildComponentId('xCwJqVo');
    $componentTag = $_instance->getRenderedChildComponentTagName('xCwJqVo');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('xCwJqVo');
} else {
    $response = \Livewire\Livewire::mount('unread-messages-count');
    $dom = $response->dom;
    $_instance->logRenderedChild('xCwJqVo', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
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
} elseif ($_instance->childHasBeenRendered('5HJTeoa')) {
    $componentId = $_instance->getRenderedChildComponentId('5HJTeoa');
    $componentTag = $_instance->getRenderedChildComponentTagName('5HJTeoa');
    $dom = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('5HJTeoa');
} else {
    $response = \Livewire\Livewire::mount('search-creators', ['search_type' => 'desktop']);
    $dom = $response->dom;
    $_instance->logRenderedChild('5HJTeoa', $response->id, \Livewire\Livewire::getRootElementTagName($dom));
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
<?php /**PATH /Users/crivion/Sites/fansonly/resources/views/partials/topnavi.blade.php ENDPATH**/ ?>
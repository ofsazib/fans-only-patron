<?php $__env->startPush( 'extraJS' ); ?>

    <script>
        // audience size slider
        AUDIENCE_MIN = <?php echo e(opt('SL_AUDIENCE_MIN', 10)); ?>;
        AUDIENCE_MAX = <?php echo e(opt('SL_AUDIENCE_MAX', 9000)); ?>;
        AUDIENCE_PREDEFINED_NO = <?php echo e(opt('SL_AUDIENCE_PRE_NUM', 100)); ?>;

        // membership fee slider
        MEMBERSHIP_FEE_MIN = <?php echo e(opt('MSL_MEMBERSHIP_FEE_MIN', 9)); ?>;
        MEMBERSHIP_FEE_MAX = <?php echo e(opt('MSL_MEMBERSHIP_FEE_MAX', 999)); ?>;
        MEMBERSHIP_FEE_PRESET = <?php echo e(opt('MSL_MEMBERSHIP_FEE_PRESET', 9)); ?>;
    </script>

    <script src="<?php echo e(asset('js/carouselscript.js')); ?>"></script>
    <script src="<?php echo e(asset('js/owl.carousel.js')); ?>"></script>
    <script src="<?php echo e(asset('js/jquery.mb.slider.js')); ?>"></script>
    <script src="<?php echo e(asset('js/homepage-sliders-v2x.js')); ?>?v=<?php echo e(microtime()); ?>"></script>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <div class="banner_sec">
        <div class="banner_box">
            <div class="banner_innr">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="banner_details">
                                <b class="ban_heading"><?php echo e(opt('homepage_headline')); ?></b>
                                <p><?php echo clean(opt('homepage_intro')); ?></p>
                                <a href="<?php echo e(route('browseCreators')); ?>"
                                   class="explore_btn"><?php echo app('translator')->get('homepage.exploreCreators'); ?></a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="banner_pic">
                                <?php if($headerImage = opt('homepage_header_image')): ?>
                                    <img src="<?php echo e(asset($headerImage)); ?>" alt="">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('images/banpic.png')); ?>" alt=""/>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="#bottom" class="btm-arrow scroll"><img src="<?php echo e(asset('images/arw.png')); ?>" alt=""/></a>
    </div>



    <section class="welcome_sec" id="bottom">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-8 offset-0 offset-sm-0 offset-md-2">
                    <?php echo clean(opt('home_callout_formatted')); ?>

                </div>
            </div>
        </div>
    </section>


    <section class="mid_sec">
        <div class="container">
            <div class="mid_row">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mid_pic">
                            <img src="<?php echo e(asset('images/midpic1.png')); ?>" alt=""/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mid_txt top_heading">
                            <h1><?php echo e(opt('homepage_left_title')); ?></h1>
                            <?php echo clean(opt('home_left_content')); ?>

                            <a href="<?php if(auth()->guest()): ?> <?php echo e(route('register')); ?> <?php else: ?> <?php echo e(route('profile.show', ['username' => auth()->user()->profile->username ])); ?> <?php endif; ?>"
                               class="btn_txt">
                                <?php if( auth()->guest() ): ?>
                                    <?php echo app('translator')->get('navigation.startMyPage'); ?>
                                <?php else: ?>
                                    <?php echo app('translator')->get('navigation.myProfile'); ?>
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mid_row">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mid_pic">
                            <img src="<?php echo e(asset('images/midpic2.png')); ?>" alt=""/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mid_txt top_heading">
                            <h1><?php echo e(opt('homepage_right_title')); ?></h1>
                            <?php echo clean(opt('home_right_content')); ?>

                            <a href="<?php if(auth()->guest()): ?> <?php echo e(route('register')); ?> <?php else: ?> <?php echo e(route('profile.show', ['username' => auth()->user()->profile->username ])); ?> <?php endif; ?>"
                               class="btn_txt">
                                <?php if( auth()->guest() ): ?>
                                    <?php echo app('translator')->get('navigation.login'); ?>
                                <?php else: ?>
                                    <?php echo app('translator')->get('navigation.feed'); ?>
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="creator_sec">
        <div class="container">
            <div class="top_heading">
                <h2><?php echo app('translator')->get('homepage.randomCreators'); ?></h2>
            </div>
            <?php echo $__env->make('creators.loop', ['creators' => $creators], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <a href="<?php echo e(route('browseCreators')); ?>" class="browse_btn"><?php echo app('translator')->get('homepage.browseCreators'); ?></a>
        </div>
    </section>



    <section class="fees_sec">
        <div class="container">
            <div class="fees_innr">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mid_txt top_heading">
                            <h1><?php echo app('translator')->get('homepage.feesExplained'); ?></h1>
                            <p>
                                <?php echo e(__('homepage.feesExplained1', [ 'site_fee' => opt( 'payment-settings.site_fee' ) . '%'])); ?>

                                <br/><br/>
                                <?php echo app('translator')->get( 'homepage.feesExplained2' ); ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mid_pic">
                            <img src="<?php echo e(asset('images/midpic3.png')); ?>" alt=""/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if(opt('hideEarningsSimulator', 'Show') == 'Show'): ?>
        <section class="calculatr_sec">
            <div class="container">
                <div class="calculatr_innr">
                    <h2><?php echo app('translator')->get( 'homepage.earningsSimulator' ); ?></h2>
                    <div class="range_mid">
                        <div id="ex_new1" class="range_box" style="">
                            <b><?php echo app('translator')->get( 'homepage.audienceSize' ); ?> </b>
                            <span id="sl2_val" class="setVal">
                    <input class="val sl_audience" value="<?php echo e(opt('SL_AUDIENCE_PRE_NUM', 100)); ?>" style="width:50px;"
                           onkeyup="$('#sl2').mbsetVal($(this).val());">
                </span>
                            <i><?php echo e(opt('SL_AUDIENCE_MAX', 1000)); ?></i>
                            <div id="sl2" class="mb_slider"
                                 data-property="{rangeColor:'orange',negativeColor:'#ffcc00', startAt:<?php echo e(opt('SL_AUDIENCE_PRE_NUM', 100)); ?>, grid:0, minVal: <?php echo e(opt('SL_AUDIENCE_MIN', 100)); ?>}"></div>
                        </div>
                        <div id="ex1" class="range_box" style="">
                            <b><?php echo app('translator')->get( 'homepage.membershipFee' ); ?> <em><?php echo e(opt( 'payment-settings.currency_symbol' )); ?></em></b>
                            <span id="sl1_val" class="setVal">
                    <input class="val sl_membership" value="<?php echo e(opt('MSL_MEMBERSHIP_FEE_PRESET', 9)); ?>"
                           style="width:50px;" onkeyup="$('#sl1').mbsetVal($(this).val());">
                </span>
                            <i><?php echo e(opt( 'payment-settings.currency_symbol' )); ?><?php echo e(opt('MSL_MEMBERSHIP_FEE_MAX', 900)); ?></i>
                            <div id="sl1" class="mb_slider"
                                 data-property="{rangeColor:'orange',negativeColor:'#ffcc00', startAt:<?php echo e(opt('MSL_MEMBERSHIP_FEE_PRESET', 9)); ?>, grid:0, minVal: <?php echo e(opt('MSL_MEMBERSHIP_FEE_MIN', 9)); ?>}"></div>
                        </div>


                    </div>
                    <div class="calculatr_btm">
                        <h3>
                            <span class="per-month-v2"><?php echo e(opt( 'payment-settings.currency_symbol' )); ?>850</span> <?php echo app('translator')->get( 'homepage.perMonth' ); ?>
                        </h3>
                        <p><?php echo e(__('homepage.calcNote', [ 'site_fee' => opt('payment-settings.site_fee').'%'])); ?></p>
                        <a href="<?php echo e(route('login')); ?>#" class="profile_btn"><?php echo app('translator')->get('homepage.startCreatorProfile'); ?></a>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('welcome', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/crivion/Sites/patrons/resources/views/homepagev2.blade.php ENDPATH**/ ?>
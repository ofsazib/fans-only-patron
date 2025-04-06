@extends('welcome')

@push( 'extraJS' )

    <script>
        // audience size slider
        AUDIENCE_MIN = {{ opt('SL_AUDIENCE_MIN', 10) }};
        AUDIENCE_MAX = {{ opt('SL_AUDIENCE_MAX', 9000) }};
        AUDIENCE_PREDEFINED_NO = {{ opt('SL_AUDIENCE_PRE_NUM', 100) }};

        // membership fee slider
        MEMBERSHIP_FEE_MIN = {{ opt('MSL_MEMBERSHIP_FEE_MIN', 9) }};
        MEMBERSHIP_FEE_MAX = {{ opt('MSL_MEMBERSHIP_FEE_MAX', 999) }};
        MEMBERSHIP_FEE_PRESET = {{ opt('MSL_MEMBERSHIP_FEE_PRESET', 9) }};
    </script>

    <script src="{{ asset('js/carouselscript.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.js') }}"></script>
    <script src="{{ asset('js/jquery.mb.slider.js') }}"></script>
    <script src="{{ asset('js/homepage-sliders-v2x.js') }}?v={{ microtime() }}"></script>

@endpush

@section('content')

    <div class="banner_sec">
        <div class="banner_box">
            <div class="banner_innr">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="banner_details">
                                <b class="ban_heading">{{ opt('homepage_headline') }}</b>
                                <p>{!! clean(opt('homepage_intro')) !!}</p>
                                <a href="{{ route('browseCreators') }}"
                                   class="explore_btn">@lang('homepage.exploreCreators')</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="banner_pic">
                                @if($headerImage = opt('homepage_header_image'))
                                    <img src="{{ asset($headerImage) }}" alt="">
                                @else
                                    <img src="{{ asset('images/banpic.png') }}" alt=""/>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="#bottom" class="btm-arrow scroll"><img src="{{ asset('images/arw.png') }}" alt=""/></a>
    </div>



    <section class="welcome_sec" id="bottom">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-8 offset-0 offset-sm-0 offset-md-2">
                    {!! clean(opt('home_callout_formatted')) !!}
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
                            <img src="{{ asset('images/midpic1.png') }}" alt=""/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mid_txt top_heading">
                            <h1>{{ opt('homepage_left_title') }}</h1>
                            {!! clean(opt('home_left_content')) !!}
                            <a href="@if(auth()->guest()) {{ route('register') }} @else {{ route('profile.show', ['username' => auth()->user()->profile->username ]) }} @endif"
                               class="btn_txt">
                                @if( auth()->guest() )
                                    @lang('navigation.startMyPage')
                                @else
                                    @lang('navigation.myProfile')
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mid_row">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mid_pic">
                            <img src="{{ asset('images/midpic2.png') }}" alt=""/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mid_txt top_heading">
                            <h1>{{ opt('homepage_right_title') }}</h1>
                            {!! clean(opt('home_right_content')) !!}
                            <a href="@if(auth()->guest()) {{ route('register') }} @else {{ route('profile.show', ['username' => auth()->user()->profile->username ]) }} @endif"
                               class="btn_txt">
                                @if( auth()->guest() )
                                    @lang('navigation.login')
                                @else
                                    @lang('navigation.feed')
                                @endif
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
                <h2>@lang('homepage.randomCreators')</h2>
            </div>
            @include('creators.loop', ['creators' => $creators])
            <a href="{{ route('browseCreators') }}" class="browse_btn">@lang('homepage.browseCreators')</a>
        </div>
    </section>



    <section class="fees_sec">
        <div class="container">
            <div class="fees_innr">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mid_txt top_heading">
                            <h1>@lang('homepage.feesExplained')</h1>
                            <p>
                                {{ __('homepage.feesExplained1', [ 'site_fee' => opt( 'payment-settings.site_fee' ) . '%']) }}
                                <br/><br/>
                                @lang( 'homepage.feesExplained2' )
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mid_pic">
                            <img src="{{ asset('images/midpic3.png') }}" alt=""/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(opt('hideEarningsSimulator', 'Show') == 'Show')
        <section class="calculatr_sec">
            <div class="container">
                <div class="calculatr_innr">
                    <h2>@lang( 'homepage.earningsSimulator' )</h2>
                    <div class="range_mid">
                        <div id="ex_new1" class="range_box" style="">
                            <b>@lang( 'homepage.audienceSize' ) </b>
                            <span id="sl2_val" class="setVal">
                    <input class="val sl_audience" value="{{ opt('SL_AUDIENCE_PRE_NUM', 100) }}" style="width:50px;"
                           onkeyup="$('#sl2').mbsetVal($(this).val());">
                </span>
                            <i>{{ opt('SL_AUDIENCE_MAX', 1000) }}</i>
                            <div id="sl2" class="mb_slider"
                                 data-property="{rangeColor:'orange',negativeColor:'#ffcc00', startAt:{{ opt('SL_AUDIENCE_PRE_NUM', 100) }}, grid:0, minVal: {{ opt('SL_AUDIENCE_MIN', 100) }}}"></div>
                        </div>
                        <div id="ex1" class="range_box" style="">
                            <b>@lang( 'homepage.membershipFee' ) <em>{{ opt( 'payment-settings.currency_symbol' )}}</em></b>
                            <span id="sl1_val" class="setVal">
                    <input class="val sl_membership" value="{{ opt('MSL_MEMBERSHIP_FEE_PRESET', 9) }}"
                           style="width:50px;" onkeyup="$('#sl1').mbsetVal($(this).val());">
                </span>
                            <i>{{ opt( 'payment-settings.currency_symbol' )}}{{ opt('MSL_MEMBERSHIP_FEE_MAX', 900) }}</i>
                            <div id="sl1" class="mb_slider"
                                 data-property="{rangeColor:'orange',negativeColor:'#ffcc00', startAt:{{ opt('MSL_MEMBERSHIP_FEE_PRESET', 9) }}, grid:0, minVal: {{ opt('MSL_MEMBERSHIP_FEE_MIN', 9) }}}"></div>
                        </div>


                    </div>
                    <div class="calculatr_btm">
                        <h3>
                            <span class="per-month-v2">{{ opt( 'payment-settings.currency_symbol' )}}850</span> @lang( 'homepage.perMonth' )
                        </h3>
                        <p>{{ __('homepage.calcNote', [ 'site_fee' => opt('payment-settings.site_fee').'%']) }}</p>
                        <a href="{{ route('login') }}#" class="profile_btn">@lang('homepage.startCreatorProfile')</a>
                    </div>
                </div>
            </div>
        </section>
    @endif

@endsection
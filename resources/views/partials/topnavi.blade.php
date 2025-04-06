<header class="header_sec innerheaders">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light nav_top">

            <a class="navbar-brand" href="{{ route('home') }}">
                @if($logo = opt('site_logo'))
                    <img src="{{ asset($logo) }}" alt="logo" class="site-logo"/>
                @else
                    {{ opt( 'site_title' ) }}
                @endif
            </a><!-- logo -->

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars text-white"></i>
            </button><!-- navbar toggler icon (mobile) -->

            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">

                @livewire('search-creators', ['search_type' => 'mobile'])

                <button class="navbar-toggler close_tgl" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <img src="{{ asset('images/close.png') }}" alt=""/>
                </button><!-- close navi on mobile -->

                <ul class="navbar-nav menu_sec">
                    @if( auth()->guest() )
                        <li>
                            <a href="/">@lang( 'navigation.home' )</a>
                        </li>
                    @endif
                    @if( !auth()->guest() )
                        <li>
                            <a href="{{ route('feed') }}">@lang('navigation.feed')</a>
                        </li>
                        <li>
                            @livewire('notifications-icon')
                        </li>
                        <li>
                            @livewire('unread-messages-count')
                        </li>
                        <li>
                            <a href="{{ route('profile.show', ['username' => auth()->user()->profile->username ]) }}">
                                @lang('navigation.myProfile')
                            </a>
                        </li>
                        <li>
                            <a href="{{  route('startMyPage') }}">
                                @lang('navigation.account')
                                @if(auth()->user()->profile->isVerified == 'Yes' && auth()->user()->profile->monthlyFee)
                                    <span class="">{{ '(' . opt('payment-settings.currency_symbol') . number_format(auth()->user()->balance,2) . ')' }}</span>
                                @endif
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('browseCreators') }}">@lang('navigation.exploreCreators')</a>
                    </li>
                    @if( auth()->guest() )
                        <li class="d-none d-sm-none d-md-block">
                            <a href="{{ route('register') }}"
                               class="border-white border-radius-account-buttons padding-account-buttons signupButton">
                                <i class="fas fa-user"></i> @lang('navigation.signUp')
                            </a>
                        </li>
                        <li class="d-none d-sm-none d-md-block">
                            <a href="{{ route('login') }}"
                               class="bg-white border-radius-account-buttons padding-account-buttons loginButton">
                                <i class="fas fa-sign-in-alt"></i> @lang('navigation.login')</a>
                        </li>
                    @endif
                    @if( !auth()->guest() )
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                @lang('navigation.logout')
                            </a>
                        </li>
                    @endif
                    @livewire('search-creators', ['search_type' => 'desktop'])
                </ul>
                <ul class="rt_btn d-lg-none d-md-block d-sm-block d-block">
                    @if( auth()->guest() )
                        <li>
                            <a href="{{ route('register') }}"
                               class="border-black border-radius-account-buttons">
                                <i class="fas fa-user"></i> @lang('navigation.signUp')
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('login') }}"
                               class="border-black border-radius-account-buttons">
                                <i class="fas fa-sign-in-alt"></i> @lang('navigation.login')</a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</header>

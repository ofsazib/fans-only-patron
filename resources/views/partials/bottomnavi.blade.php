
<footer class="footer_sec">
    <div class="container">
    <nav class="navbar navbar-expand-lg">
        <a class="h5 mt-2" href="/">&copy; {{ opt('site_title') }} {{ date('Y') }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div>
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="/">@lang( 'navigation.home' ) <span class="sr-only">(current)</span></a>
                </li>
                @forelse($all_pages as $p)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('page', ['page' => $p]) }}">{{ $p->page_title }}</a>
                    </li>
                @empty
                @endforelse
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('report') }}">@lang('v14.report-form-page')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact-page') }}">@lang('v18.contact-form-page')</a>
                </li>
            </ul>
        </div>
    </nav>
    </div>
</footer>
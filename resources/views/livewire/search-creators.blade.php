<div>
    <div class="p-relative">
        @if($search_type === 'mobile')
            <input class="d-block d-sm-block d-md-none form-control search-topnavi mt-2 mr-sm-2 " type="search" placeholder="@lang('general.searchCreator')" aria-label="Search" wire:model.debounce.200ms="search">
        @elseif($search_type === 'desktop')
            <input class="d-none d-sm-none d-md-block form-control search-topnavi mt-2 mr-sm-2 " type="search" placeholder="@lang('general.searchCreator')" aria-label="Search" wire:model.debounce.200ms="search">
        @endif
        <div class="search-spinner" wire:loading>
            <i class="fas fa-spinner fa-spin"></i>
        </div>
    </div>

    @if(strlen($search) >= 2)
    <div class="card shadow autocomplete-results">
        @if(is_object($creators) AND $creators->count())
        <div class="row">
            @foreach($creators as $p)
            <div class="col-12 col-sm-12 col-md-4 text-center">
                <a href="{{ $p->url }}">
                    <img src="{{ secure_image($p->profilePic, 150, 150) }}" alt="p pic" class="img-fluid rounded-circle ml-2 mt-1">
                </a>
                <img src="" class="rounded p-1">
            </div>
            <div class="col-12 col-sm-12 col-md-8 text-center text-sm-left">
                <a href="{{ $p->url }}" class="text-dark d-block mt-1">
                    {{ $p->name }}
                </a>
                <a href="{{ $p->url }}">
                    {{ $p->handle }}
                </a>
            </div>
            <hr>
            @endforeach
        </div>
        @endif
    </div>
    @endif
</div>

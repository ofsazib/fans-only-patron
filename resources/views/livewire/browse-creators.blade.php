<div>
    <div class="browseFilters mb-4 text-center">

    <div wire:ignore>
    <select name="category" class="selectpicker show-tick mb-2" wire:model="category">
    <option value="all" @if($category == 'all') selected="selected" @endif>@lang('dashboard.allCategories')</option>
    @forelse($all_categories AS $c)
        <option value="{{ $c->id }}" @if($category == $c->id) selected="selected" @endif>
            {{ $c->category }}
        </option>
    @empty
        <option value="">@lang('homepage.noCategories')</option>
    @endforelse
    </select>
    
    <select name="sortBy" class="selectpicker show-tick mb-2" wire:model="sortBy">
        <option value="popularity" @if('sortBy' == 'popularity') selected="selected" @endif>@lang('dashboard.sortByPopularity')</option>
        <option value="postscount" @if('sortBy' == 'postscount') selected="selected" @endif>@lang('dashboard.sortByPosts')</option>
        <option value="subscribers" @if('sortBy' == 'subscribers') selected="selected" @endif>@lang('dashboard.sortBySubscribers')</option>
        <option value="alphabetically" @if('sortBy' == 'alphabetically') selected="selected" @endif>@lang('dashboard.sortByAlphabetically')</option>
        <option value="joindate" @if('sortBy' == 'joindate') selected="selected" @endif>@lang('dashboard.sortByJoinDate')</option>
    </select>
    </div>

    </div>

    @include('creators.loop')

    <div wire:loading>
        <i class="fas fa-spinner fa-spin"></i> @lang( 'profile.pleaseWait' )
    </div>
    
</div>

<div>
    <a href="{{ route('notifications.index') }}">
        @lang('navigation.myNotifications') 
        <span class="notifc">{{ auth()->user()->unreadNotifications()->count() }}</span>
    </a>
</div>

<div wire:poll.3000ms>
<div>
    <a href="{{  route('messages.inbox') }}">
        @lang('navigation.messages')
        <span class="notifc">
            {{ $count }}
        </span>
    </a>
</div>

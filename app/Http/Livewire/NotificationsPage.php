<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class NotificationsPage extends Component
{
    use WithPagination;

    public $types;
    public $tab;

    public function mount()
    {
        $this->types = $this->notificationTypes();
        $this->tab = 'All';
    }

    public function updatedTab()
    {
        $this->resetPage();
    }

    public function notificationTypes()
    {
        return [
            'Likes' => 'App\Notifications\ReceivedLike',
            'Fans' => 'App\Notifications\NewSubscriberNotification',
            'Followers' => 'App\Notifications\NewFollower',
            'Invoices' => 'App\Notifications\InovicePaidNotification',
            'Payments' => 'App\Notifications\PaymentActionRequired',
            'Comments' => 'App\Notifications\ReceivedComment',
            'Mentions' => 'App\Notifications\ReceivedPostMentionNotification',
            'Tips'    => 'App\Notifications\TipReceivedNotification',
            'Unlocks'    => 'App\Notifications\UnlockedMessageNotification',
        ];
    }

    public function render()
    {
        if (!auth()->check())
            abort(403);

        // get this user notifications
        $notifications = auth()->user()->notifications();

        // filter tab 
        if ($this->tab != 'All')
            $notifications->where('type', $this->types[$this->tab]);

        $notifications = $notifications->paginate(10);

        return view('livewire.notifications-page', compact('notifications'));
    }
}

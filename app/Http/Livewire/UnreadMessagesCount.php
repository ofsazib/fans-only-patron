<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Message;

class UnreadMessagesCount extends Component
{
    public function render()
    {
        $count = Message::where('to_id', auth()->id())->where('is_read', 'No')->count();
        return view('livewire.unread-messages-count', compact('count'));
    }
}

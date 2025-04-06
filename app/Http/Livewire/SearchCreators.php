<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Profile;

class SearchCreators extends Component
{
    public $search;
    public $search_type;

    public function mount($search_type)
    {
        $this->search = '';
        $this->search_type = $search_type;
    }

    public function render()
    {
        $search = trim($this->search);

        if (strlen($search) >= 2) {
            $creators = Profile::where('isVerified', 'Yes')
                ->where(function ($q) use ($search) {
                    $q->orWhere('username', 'LIKE', '%' . $search . '%')
                        ->orWhere('name', 'LIKE', '%' . $search . '%');
                })
                ->orderBy('name')
                ->take(5)
                ->get();
        } else {
            $creators = '';
        }

        return view('livewire.search-creators', compact('creators'));
    }
}

<?php

namespace App\Http\Livewire;

use App\Profile;
use Livewire\Component;
use Livewire\WithPagination;

class CreatorsSidebar extends Component
{
    use WithPagination;

    public function render()
    {
        // get a list of all creators, sorted by popularity
        // current user must not already follow
        $followList = [];
        $followList[] = auth()->id();

        if (auth()->check()) {
            $userAlreadyFollowing = auth()->user()->followings;

            foreach ($userAlreadyFollowing as $f) {
                $followList[] = $f->id;
            }
        } else {
            $followList = [];
        }

        $creators = Profile::where('isVerified', 'Yes')
            ->with('category')
            ->whereHas('category')
            ->orderByDesc('popularity')
            ->withCount('posts')
            ->whereNotIn('user_id', $followList);

        // if hide admin from creators
        if (opt('hide_admin_creators', 'No') == 'Yes') {
            $creators->join('users', 'creator_profiles.user_id', 'users.id')
                     ->where('users.isAdmin', '!=', 'Yes');
        }
        
        $creators = $creators->simplePaginate(2);


        $cols = 12;

        return view('livewire.creators-sidebar', compact('creators', 'cols'));
    }
}

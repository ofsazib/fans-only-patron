<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Profile;
use Overtrue\LaravelFollow\Events\Followed;

class Followbutton extends Component
{
    public $profileId;

    public function mount($profileId)
    {
        $this->profileId = $profileId;
    }

    public function getProfileProperty()
    {
        return Profile::findOrFail($this->profileId);
    }

    public function render()
    {
        return view('livewire.followbutton');
    }

    public function toggleFollow()
    {

        if (!auth()->check())
            return redirect(route('login'));

        if (auth()->user()->id != $this->profile->user->id) {

            // toggle follow
            $follow = auth()->user()->toggleFollow($this->profile->user);

            // notify user
            if (!is_null($follow))
                return event(new Followed($follow));

            return $follow;
        } else {

            $this->emit('swal', [
                'type'  => 'error',
                'title' => '',
                'message'  => __('profile.followSelf'),
            ]);
        }
    }
}

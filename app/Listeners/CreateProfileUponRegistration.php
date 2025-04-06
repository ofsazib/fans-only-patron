<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

use App\User;
use App\Profile;
use App\Category;

class CreateProfileUponRegistration
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle($event)
    {

        // get the user
        $user = $event->user;

        // create new profile
        $profile = new Profile;
        $profile->name = $user->name;
        $profile->username = Str::slug($user->name . $user->id);
        $profile->user_id = $user->id;
        $profile->category_id = Category::first()->id;
        $profile->profilePic = 'profilePics/default-profile-pic.png';
        $profile->save();

        // check if user needs to be verified by admin, if no verification required -> promote this user to creator
        if(opt('requireVerification', 'Yes') === 'No') {

            $profile->isVerified = 'Yes';
            $profile->save();

        }
    }
}

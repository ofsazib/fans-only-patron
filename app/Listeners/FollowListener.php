<?php

namespace App\Listeners;

use App\Notifications\NewFollower;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Overtrue\LaravelFollow\Events\Followed;

class FollowListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Followd  $event
     * @return void
     */
    public function handle(Followed $event)
    {
        // get followed user
        $followed = User::findOrFail($event->followingId);

        // get follower profile
        $follower = User::select('id', 'name')
            ->without('likes')
            ->with('profile:id,user_id,username,profilePic')
            ->findOrFail($event->followerId);

        // send notification
        $followed->notify(new NewFollower($follower));

        // increase popularity of this user
        $followed->profile->increment('popularity');
    }
}

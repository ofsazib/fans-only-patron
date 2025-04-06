<?php

namespace App\Listeners;

use App\Events\PostCreatedOrUpdatedEvent;
use App\Profile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\ReceivedPostMentionNotification;

class PostCreatedOrUpdatedListener
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
     * @param  PostCreatedOrUpdatedEvent  $event
     * @return void
     */
    public function handle(PostCreatedOrUpdatedEvent $event)
    {
        // get post
        $post = $event->post;

        // get owner
        $owner = $post->user;

        // check if contains handle and notify
        $this->checkHandleInPostContent($post);
    }

    public function checkHandleInPostContent($post)
    {
        preg_match_all('/@[a-zA-Z0-9\-_]+/i', $post->text_content, $matches);

        if (!count($matches))
            return false;

        $matches = reset($matches);

        foreach ($matches as $m) {
            if ($p = $this->doesHandleExists($m)) {

                // notify user
                $p->user->notify(new ReceivedPostMentionNotification($post));

                // increase popularity of this user
                $p->increment('popularity');
            }
        }
    }

    public function doesHandleExists($handle)
    {
        // set username
        $username = str_ireplace('@', '', $handle);

        // find profile
        $profile = Profile::with('user')->where('username', $username)->first();

        return $profile;
    }

}

<?php

namespace App\Providers;

use App\Events\NewCommentEvent;
use App\Events\PostCreatedOrUpdatedEvent;
use Overtrue\LaravelFollow\Events\Followed;
use Illuminate\Auth\Events\Registered;
use Overtrue\LaravelLike\Events\Liked;

use App\Listeners\LikeListener;
use App\Listeners\FollowListener;
use App\Listeners\NewCommentListener;
use App\Listeners\PostCreatedOrUpdatedListener;

use Illuminate\Support\Facades\Event;

use App\Listeners\CreateProfileUponRegistration;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            CreateProfileUponRegistration::class,
        ],
        Liked::class => [
            LikeListener::class,
        ],
        Followed::class => [
            FollowListener::class,
        ],
        NewCommentEvent::class =>
        [
            NewCommentListener::class,
        ],
        PostCreatedOrUpdatedEvent::class =>
        [
            PostCreatedOrUpdatedListener::class,
        ]

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}

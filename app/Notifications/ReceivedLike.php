<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReceivedLike extends Notification
{
    use Queueable;

    public $like;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($like)
    {
        $this->like = $like;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // add 'mail' to send like notifications
        return ['database'];
    }


    public function toMail($notifiable)
    {
        $uri = route('single-post', ['post' => $this->like['postId']]);
        $user = route('profile.show', ['username' => $this->like['user']]);

        return (new MailMessage)
            ->subject("You've got a new like")
            ->greeting("You've got a new like")
            ->line('Hey,')
            ->line(new HtmlString('<a href="' . $user . '">@' . $this->like['user'] . '</a> has liked your post:'))
            ->line(new HtmlString('<a href="' . $uri . '">' . $uri . '</a>'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->like;
    }
}

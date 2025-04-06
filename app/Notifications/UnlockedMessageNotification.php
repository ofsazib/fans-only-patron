<?php

namespace App\Notifications;

use App\Mail\UnlockedMessageMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UnlockedMessageNotification extends Notification
{
    use Queueable;

    public $unlock;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($unlock)
    {
        $this->unlock = $unlock;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new UnlockedMessageMail($this->unlock, $notifiable))
            ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'amount' => $this->unlock->creator_amount,
            'from_user' => $this->unlock->tipper->profile->username,
            'from_handle' => $this->unlock->tipper->profile->handle
        ];
    }
}

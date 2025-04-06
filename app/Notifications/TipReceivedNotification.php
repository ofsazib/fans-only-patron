<?php

namespace App\Notifications;

use App\Mail\TipReceivedEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TipReceivedNotification extends Notification
{
    use Queueable;

    public $tip;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tip)
    {
        $this->tip = $tip;
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
        return (new TipReceivedEmail($this->tip, $notifiable))
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
            'amount' => $this->tip->creator_amount,
            'from_user' => $this->tip->tipper->profile->username,
            'from_handle' => $this->tip->tipper->profile->handle
        ];
    }
}

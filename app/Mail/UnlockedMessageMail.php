<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UnlockedMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $unlock;
    public $notifiable;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($unlock, $notifiable)
    {
        $this->unlock = $unlock;
        $this->notifiable = $notifiable;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('v19.unlockedMessageMail'))->markdown('emails.unlockedMessageMail');
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TipReceivedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $tip;
    public $notifiable;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tip, $notifiable)
    {
        $this->tip = $tip;
        $this->notifiable = $notifiable;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mail.tipReceivedMail'))->markdown('emails.tipReceivedMail');
    }
}

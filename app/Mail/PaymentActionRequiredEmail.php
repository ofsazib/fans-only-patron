<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentActionRequiredEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $notifiable;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invoice, $notifiable)
    {
        $this->invoice = $invoice;
        $this->notifiable = $notifiable;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mail.paymentActionRequired'))
            ->markdown('emails.paymentActionRequired');
    }
}

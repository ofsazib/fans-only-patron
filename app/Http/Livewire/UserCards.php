<?php

namespace App\Http\Livewire;

use App\PaymentMethod;
use Livewire\Component;
use Stripe\StripeClient as StripeClient;

class UserCards extends Component
{
    public $displayConfirm = 'd-none';
    public $confirmDeleteCardId;

    // confirm delete
    public function confirmDelete($cardId)
    {
        $this->displayConfirm = 'd-block';
        $this->confirmDeleteCardId = $cardId;
    }

    // cancel card removal
    public function cancelCardRemoval()
    {
        $this->displayConfirm = 'd-none';
    }

    // remove card
    public function removeCard($cardId)
    {
        $this->displayConfirm = 'd-none';
        $this->confirmDeleteCardId = '';

        auth()->user()->paymentMethods()->where('id', $cardId)->delete();
    }

    // set default
    public function setDefault($cardId)
    {
        try {

            // get payment method details
            $pm = auth()->user()->paymentMethods()->where('id', $cardId)->firstOrFail();

            // set default card depending on gateway
            if(opt('card_gateway', 'Stripe') == 'Stripe') {

                // update default payment method on stripe
                $stripe = new StripeClient(opt('STRIPE_SECRET_KEY', '123'));

                // update user default payment method
                $updateDefaultPm = $stripe->customers->update('fan_' . auth()->id(), [
                    'invoice_settings' => [
                        'default_payment_method' => $pm->p_meta['payment_method']
                    ]
                ]);

            }

            // reset other payment cards
            auth()->user()->paymentMethods()->update(['is_default' => 'No']);

            // attach payment method to this user
            auth()->user()->paymentMethods()->where('id', $cardId)->update([
                'is_default'    => 'Yes',
            ]);
        } catch (\Exception $e) {
            $this->emit('growl', [
                'title' => $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        // get user cards
        $cards = auth()->user()->paymentMethods;

        return view('livewire.user-cards', compact('cards'));
    }
}

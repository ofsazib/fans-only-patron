<?php

namespace App\Http\Livewire;

use App\Options;
use Composer\DependencyResolver\Request;
use Livewire\Component;

class AdminPaymentSettings extends Component
{

    public $gateway;

    public function mount()
    {
        $this->gateway = opt('card_gateway', 'None');
    }
    
    public function render()
    {
        return view('livewire.admin-payment-settings');
    }
}

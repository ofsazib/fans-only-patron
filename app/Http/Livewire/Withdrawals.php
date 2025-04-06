<?php

namespace App\Http\Livewire;

use App\Mail\PaymentRequestCreated;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class Withdrawals extends Component
{
    use WithPagination;

    public $tab = 'Pending';

    public function mount()
    {
        if (auth()->guest())
            abort(403);
    }

    public function tab($tab)
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function cancelPending($id)
    {
        $w = auth()->user()->withdrawals()->where('withdraws.id', $id)->firstOrFail();

        if ($w->status != 'Pending')
            abort(403);

        $w->status = 'Canceled';
        $w->save();

        $this->emit('swal', [
            'icon' => 'success',
            'title' => __('general.successfullyCanceledWithdrawal')
        ]);
    }

    public function sendRequest()
    {

        if (auth()->user()->profile->payout_gateway == 'None' or empty(auth()->user()->profile->payout_details)) {

            $this->emit('swal', [
                'icon' => 'error',
                'message' => __('general.payoutMethodNotSet') . __('dashboard.creatorSetup')
            ]);

            return;
        }


        $amount = auth()->user()->balance;

        $pendingWithdrawals = auth()->user()->withdrawals()
            ->where('status', 'Pending')
            ->exists();

        if ($pendingWithdrawals) {

            $this->emit('swal', [
                'icon' => 'error',
                'message' => __('general.waitUntilPending')
            ]);

            return;
        }

        if ($amount < opt('withdraw_min', 20)) {

            $minAmount = opt('payment-settings.currency_symbol') . opt('withdraw_min', 20);

            $this->emit('swal', [
                'icon' => 'error',
                'message' => __(
                    'general.withdrawMin',
                    [
                        'minWithdrawAmount' => $minAmount,
                    ]
                )
            ]);
        } else {

            // create withdrawal
            $w = auth()->user()->withdrawals()->create([
                'amount' => $amount,
                'created_at' => Carbon::now(),
            ]);

            // notify admin
            $adminEmail = opt('admin_email', 'support@yoursite.com');
            Mail::to($adminEmail)->send(new PaymentRequestCreated($w));

            // emit message
            $this->emit('swal', [
                'icon' => 'success',
                'message' => __('general.withdrawSent')
            ]);
        }
    }

    public function render()
    {
        $tab = $this->tab;

        $user = auth()->user();

        $withdrawals = $user->withdrawals()
            ->where('status', $tab)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('livewire.withdrawals', compact('withdrawals'));
    }
}

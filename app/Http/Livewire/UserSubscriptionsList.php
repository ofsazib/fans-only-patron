<?php

namespace App\Http\Livewire;

use App\Subscription;
use Livewire\Component;
use Livewire\WithPagination;


class UserSubscriptionsList extends Component
{
    use WithPagination;

    public $tab;

    protected $listeners = ['cancelSubscription'];

    public function mount()
    {
        $this->tab = 'Free';
    }

    public function tab($tab)
    {
        $this->resetPage();
        $this->tab = $tab;
    }

    public function confirmCancellation($subscriptionId)
    {
        $this->emit('swal-confirm', [
            'title' => __('general.areYouSureToCancel'),
            'message' => '',
            'emitEvent' => 'cancelSubscription',
            'parameter' => $subscriptionId,
        ]);
    }

    public function cancelSubscription($subscriptionId)
    {

        try {

            // find subscription
            $subscription = Subscription::findOrFail($subscriptionId);

            if ($subscription->gateway == 'Card') {

                // stripe client
                $stripe = new \Stripe\StripeClient(opt('STRIPE_SECRET_KEY', '123'));

                // cancel subscription
                $stripe->subscriptions->cancel($subscription->subscription_id, []);

                // update db
                $subscription->status = 'Canceled';
                $subscription->save();

                $this->emit('swal', [
                    'title' => __('general.cardRenewalCanceled'),
                    'message' => '',
                    'type' => 'info',
                ]);
            } elseif ($subscription->gateway == 'PayPal') {

                $this->emit('swal', [
                    'title' => __('general.paypalCancelMessage'),
                    'message' => '',
                    'type' => 'info',
                ]);

            } elseif($subscription->gateway == 'PayStack') {

                $this->emit('swal', [
                    'title' => __('v17.payStackCancelMessage'),
                    'message' => '',
                    'type' => 'info',
                ]);

            } elseif($subscription->gateway == 'CCBill') {

                $datalinkUser = 'memom1';
                $datalinkPass = '8qC$0gB$';
                $url = 'https://datalink.ccbill.com/utils/subscriptionManagement.cgi?clientAccnum='.opt('ccbill_clientAccnum').'&username='.$datalinkUser.'&password='.$datalinkPass.'&action=cancelSubscription&subscriptionId=' . $subscription->subscription_id;

                $ch = curl_init($url);
                curl_exec($ch);

                if(curl_error($ch)) {
                    $this->emit('swal', [
                        'title' => 'Curl Error',
                        'message' => '',
                        'type' => 'error'
                    ]);
                }
                curl_close($ch);

                // update db
                $subscription->status = 'Canceled';
                $subscription->save();

                $this->emit('swal', [
                    'title' => 'Subscription Canceled',
                    'message' => '',
                    'type' => 'info',
                ]);

            }else{

                $this->emit('swal', [
                    'title' => __('v17.invalidGatewayCancelMessage'),
                    'message' => '',
                    'type' => 'info',
                ]);

            }
        } catch (\Exception $e) {
            $this->emit('swal', [
                'title' => __('general.error'),
                'message' => $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    public function render()
    {

        if ($this->tab == 'Free') {

            $subscribers = auth()->user()->followings()
                ->simplePaginate(opt('followListPerPage', 60));
        } elseif ($this->tab == 'Paid') {

            $subscribers = auth()->user()->subscriptions()->with('creator')
                ->where('subscription_expires', '>=', now())
                ->simplePaginate(opt('followListPerPage', 60));
        }

        return view('livewire.user-subscriptions-list')
            ->with('subscribers', $subscribers);
    }

    public function unfollow($userId)
    {
        // toggle follow
        auth()->user()->toggleFollow($userId);
    }
}

<?php

namespace App\Http\Controllers;

use App\User;
use App\Subscription;
use Illuminate\Http\Request;
use App\Notifications\NewSubscriberNotification;

class MercadoPagoController extends Controller
{
    // auth middleware
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'webhooks']);
    }

    // subscribe to user
    public function subscribeToUser(User $user)
    {
        
        if (auth()->id() == $user->id) {
			alert()->info(__('general.dontSubscribeToYourself'));
			return back();
		}

        try {
            // set mercadopago secret key
            \MercadoPago\SDK::setAccessToken(opt('MERCADOPAGO_SECRET_KEY'));
            
            // Create a preference object
            $preference = new \MercadoPago\Preference();

            // set amount
            $amount = $user->profile->finalPrice;

            // Create a preference item
            $item = new \MercadoPago\Item();
            $item->title = __('1 Month Subscription to ') . $user->profile->handle;
            $item->quantity = 1;
            $item->unit_price = number_format($amount, 2);
            $item->currency_id = opt('payment-settings.currency_code', 'USD');

            // append item to preference
            $preference->items = [$item];

            // add auto-return
            $preference->auto_return = 'approved';
            
            // add return url
            $preference->back_urls = [ 'success' => route('mercadoPagoSubscriptionIPN') ];

            // compute price
            $price = $amount;

            // get platform fee
            $platform_fee = opt('payment-settings.site_fee');
            $fee_amount = ($price * $platform_fee) / 100;

            // compute creator amount
            $creator_amount = number_format($price - $fee_amount, 2);
            
            // create 'pending' subscription
			$subPlan = new Subscription;
			$subPlan->creator_id = $user->id;
			$subPlan->subscriber_id = auth()->user()->id;
			$subPlan->subscription_id = 0;
			$subPlan->gateway = 'Card';
			$subPlan->subscription_date = now();
			$subPlan->subscription_expires = now();
			$subPlan->subscription_price = $price;
			$subPlan->creator_amount = $creator_amount;
			$subPlan->admin_amount = $fee_amount;
			$subPlan->save();

            // add subscription id into reference
            $preference->external_reference = $subPlan->id;

            // exclude cash
            $preference->payment_methods = array(
                "excluded_payment_types" => array(
                  array("id" => "cash")
                )
            );

            // add subscription id into session
            session(['mgpgoSubscrId' => $subPlan->id]);

            // save
            $preference->save();

            // binary only
            $preference->binary_mode = true;

            // redirect to payment (live)
            return redirect($preference->init_point);

        } catch(\Exception $e) {
            dd($e);
            dd($e->getMessage());
        }

    }

    // process subscription
    public function mercadoPagoSubscriptionIPN(Request $r)
    {
        try {

            // if payment not approved
            if($r->status != 'approved') {
                throw new \Exception('Payment failed');
            }

            // get session tip id
            if( session()->has('mgpgoSubscrId') ) {

                // set subscr id
                $subscrId = session('mgpgoSubscrId');

                // delete from session
                session()->forget('mgpgoSubscrId');

            }elseif( $r->has('external_reference') ){

                // set tip id
                $subscrId = $r->external_reference;

            }

            // find this subscription
            $subscription = Subscription::findOrFail($subscrId);

            // update expiration date
            $subscription->subscription_expires = strtotime("+1 Month");
            $subscription->save();

            // notify fan on site & email
            $subscriber = $subscription->subscriber;

            // notify creator on site & email
            $creator = $subscription->creator;
            $creator->notify(new NewSubscriberNotification($subscriber));

            // update creator balance
            $creator->balance += $subscription->creator_amount;
            $creator->save();

            alert(__('general.subscriptionProcessing'));

        }catch(\Exception $e) {

            alert()->error($e->getMessage());
        }

        // redirect to feed
        return redirect(route('feed'));
    }

    // store token
    public function storeAuthorization(Request $r)
    {

        $this->validate($r, ['token' => 'required', 
                            'payment_method_id' => 'required', 
                            'installments' => 'required', 
                            'issuer_id' => 'required']);

        // if no creator into session
        if(!session()->has('creator')) {
            alert()->error('MGPGO: page accessed in error!');
            return redirect(route('feed'));
        }

        // set secret key
        $secretKey = opt('MERCADOPAGO_SECRET_KEY');

        // set creator
        $creator = session('creator');

        // remove creator from session
        session()->forget('creator');

        // amount
        $amount = number_format($creator->profile->finalPrice, 2);

        // get site currency
        $siteCurrency = opt('payment-settings.currency_code', 'USD');
        $siteCurrency = strtoupper($siteCurrency);

        // set & encode data
        $data = [
            'back_url' => route('profile.show', ['username' => $creator->profile->username]),
            'external_reference' => $creator->id,
            'reason' => 'Subscription ' . uniqid(),
            'card_token_id' => $r->token,
            'auto_recurring' => [
              'frequency' => 1,
              'frequency_type' => 'months',
              'transaction_amount' => $amount,
              'currency_id' => $siteCurrency,
            ]
        ];

        $data_string = json_encode($data);                                                                                                                                                          

        // create plan
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.mercadopago.com/preapproval_plan');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $secretKey;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'MercadoPago API Returned this Error:' . curl_error($ch);
            exit;
        }
        curl_close($ch);

        dd(json_decode($result));
    
    }

}

<?php

namespace App\Http\Controllers;

use App\Post;
use App\Tips;
use App\User;
use App\Unlock;
use App\Invoice;
use App\Message;
use Carbon\Carbon;
use App\Subscription;
use Illuminate\Http\Request;

use function GuzzleHttp\json_decode;
use App\Notifications\InovicePaidNotification;
use App\Notifications\TipReceivedNotification;
use App\Notifications\NewSubscriberNotification;
use App\Notifications\UnlockedMessageNotification;

class PayStackController extends Controller
{

    // auth middleware
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['webhooks', 'link']]);
    }

    // add PayStack Card
    public function addPayStackCard()
    {
        
        try {
            // compute charge amount
            $minChargeAmount = ['NGN' => '100.00', 'GHS' => '0.20', 'USD' => 0.50];

            // get site currency
            $siteCurrency = opt('payment-settings.currency_code', 'USD');
            $siteCurrency = strtoupper($siteCurrency);

            // set min charge amount
            if (isset($minChargeAmount[$siteCurrency])) {
                $minChargeAmount = $minChargeAmount[$siteCurrency];
            } else {
                throw new \Exception(__('v17.currencyError'));
            }

            return view('billing.paystack-add-card', compact('minChargeAmount'));

        }catch(\Exception $e) {

            alert()->error($e->getMessage());
            return back();

        }
    }

    // redirect to authorization
    public function redirectToAuthorization()
    {
        
        try {
            // compute charge amount
            $minChargeAmount = ['NGN' => '100.00', 'GHS' => '0.20', 'USD' => 0.50];

            // get site currency
            $siteCurrency = opt('payment-settings.currency_code', 'USD');
            $siteCurrency = strtoupper($siteCurrency);

            // set min charge amount
            if (isset($minChargeAmount[$siteCurrency])) {
                $minChargeAmount = $minChargeAmount[$siteCurrency];
            } else {
                throw new \Exception(__('v17.currencyError'));
            }

            // get currency
            $currencyCode = opt('payment-settings.currency_code', 'USD');

            // set paystack redirect url
            $url = "https://api.paystack.co/transaction/initialize";

            $fields = [
              'email' => auth()->user()->email,
              'amount' => number_format($minChargeAmount*100,0),
              'currency' => $currencyCode,
              'callback_url' => route('paystack-authorization-callback'),
              'metadata' => [ 'user' => auth()->id() ], 
              'reusable' => true
            ];

            $fields_string = http_build_query($fields);

            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_POST, true);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              "Authorization: Bearer " . opt('PAYSTACK_SECRET_KEY'),
              "Cache-Control: no-cache",
            ));
            
            //So that curl_exec returns the contents of the cURL; rather than echoing it
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
            
            //execute post
            $result = curl_exec($ch);

            if($e = curl_error($ch) && !empty($e) )
                throw new \Exception('PayStack cURL Error: ' . $e);

            // close the connection
            curl_close($ch);

            // decode result
            $result = json_decode($result);

            if(!$result->status)
                throw new \Exception('PayStack Returned this status: ' . $result->message);

            // get redirect url
            $redirectUrl = $result->data->authorization_url;

            // redirect to payment
            return redirect($redirectUrl);

        }catch(\Exception $e) {

            alert()->error($e->getMessage());
            return back();

        }

    }

    // Store Card Authorization
    public function storeAuthorization(Request $r)
    {

        // validate fields
        $this->validate($r, ['trxref' => 'required', 
                                'reference' => 'required']);

        try {

            // verify transaction
            $curl = curl_init();
  
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $r->reference,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . opt('PAYSTACK_SECRET_KEY'),
                "Cache-Control: no-cache",
                ),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            
            // handle curl error
            if ($err) {
                throw new \Exception("cURL Error #:" . $err);
            } else {
                $result = json_decode($response);
            }

            // handle gateway error
            if(!$result->status)
                throw new \Exception('PayStack Returned this status: ' . $result->message);

            // set auth data
            $data=  $result->data->authorization;

            // reset other payment cards
            auth()->user()->paymentMethods()->update(['is_default' => 'No']);

            // attach payment method to this user
            auth()->user()->paymentMethods()->create([
                'gateway'       => 'PayStack',
                'p_meta'        => $data,
                'is_default'    => 'Yes'
            ]);

            alert()->success(__('v17.cardSuccessfullyAuthorized'));

        }catch(\Exception $e) {
            alert()->error($e->getMessage());
        }

        return redirect(route('billing.cards'));

    }

    // Listen to PayStack webhooks
    public function webhooks(Request $r)
    {

        // get PayStack status
        if (opt('card_gateway', 'Stripe') != 'PayStack') {
            $error = 'Webhooks request sent to PayStack instead of ' . opt('card_gateway');

            \Log::info($error);

            return response($error);

        }

        // get paystack secret key
        $secret = opt('PAYSTACK_SECRET_KEY');

        // validate event do all at once to avoid timing attack
        $input = @file_get_contents("php://input");
        if($_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] !== hash_hmac('sha512', $input, $secret)) {

            $error = 'PayStack Webhooks does not match signature check, received: ' . trim(strip_tags($_SERVER['HTTP_X_PAYSTACK_SIGNATURE']));

            \Log::info($error);

            return response($error);

        }


        // parse event (which is json string) as object
        $event = json_decode($input);

        \Log::info('PayStack Webhook decoded json  below');
        \Log::info(var_export($event,true));

        try {

            // Handle the event
            switch ($event->event) {
        
                case 'subscription.create':

                    // get data
                    $data = $event->data;

                    // get amount
                    $price = $data->amount/100;

                    // get subscription id
                    $subscrId = $data->subscription_code;

                    // find this subscription
                    $subscription = Subscription::where('subscription_id', $subscrId)->firstOrFail();
                    $subscription->subscription_expires = Carbon::now()->addMonths(1);
                    $subscription->save();

                    // notify creator on site & email
                    $creator = $subscription->creator;
                    $creator->notify(new NewSubscriberNotification($subscription->subscriber));

                    // update creator balance
                    $creator->balance += $subscription->creator_amount;
                    $creator->save();

                    // insert the invoice with payment_status 'Paid'
                    $i = new Invoice;
                    $i->invoice_id = 0;
                    $i->user_id = 0;
                    $i->subscription_id = 0;
                    $i->amount = $price;
                    $i->payment_status = 'Paid';
                    $i->invoice_url = '--';
                    $i->save();

                    break;

                case 'subscription.disable':

                    // get data
                    $data = $event->data;

                    // get subscription id
                    $subscrId = $data->subscription_code;

                    // find this subscription
                    $subscription = Subscription::where('subscription_id', $subscrId)->firstOrFail();
                    $subscription->status = 'Canceled';
                    $subscription->save();

                    break;

                case 'invoice.create':

                    $data = $event->data;

                    if(!isset($data->transaction)) 
                        return response('Webhook does not contain subscription | transaction ref data');

                    // get transaction reference
                    $txref = $data->transaction->reference;
                    $invId = $data->transaction->invoice_code;

                    // get subscription id
                    $subscrId = $data->subscription->subscription_code;

                    // find subscription for this invoice
                    $subscription = Subscription::where('subscription_id', $subscrId)->firstOrFail();

                    // insert the invoice with payment_status 'Action Required'
                    $i = Invoice::where('invoice_id', $txref)->firstOr(function () use ($txref, $invId, $subscription) {

                        $i = new Invoice;
                        $i->invoice_id = $txref;
                        $i->user_id = $subscription->subscriber_id;
                        $i->subscription_id = $subscription->id;
                        $i->amount = $subscription->subscription_price;
                        $i->payment_status = 'Created';
                        $i->invoice_url = $invId;
                        $i->save();

                    });

                    break;

                case 'charge.success':

                    // get data
                    $data = $event->data;

                    // get amount
                    $price = $data->amount/100;

                    // get metadata
                    $metadata = $data->metadata;

                    \Log::info(var_export($metadata,true));

                    // check if it's a tip (and process it) or authorization (and skip if auth)
                    if(!isset($metadata->post) AND !isset($data->plan) AND !isset($data->message_id)) {

                        \Log::info('PayStack Charge.Success, must be auth -> skipping');
                        return response('Webhook Handled');

                    }

                    // process Tip
                    if (isset($metadata->post)) {

                        // find this post
                        $post = Post::findOrFail($metadata->post);

                        // find this creator
                        $creator = User::findOrFail($metadata->creator);

                        // find this subscriber
                        $subscriber = User::findOrFail($metadata->tipper);

                        // get platform fee
                        // $platform_fee = opt('payment-settings.site_fee');
                        $platform_fee = $creator->userFee;
                        $fee_amount = ($price * $platform_fee) / 100;

                        // compute creator amount
                        $creator_amount = number_format($price - $fee_amount, 2);
                        
                        // update creator balance
                        $creator->balance += $creator_amount;
                        $creator->save();

                        // now that we validated everything, let's insert the tip in database and notify the creator
                        $tip = new Tips;
                        $tip->tipper_id = $subscriber->id;
                        $tip->creator_id = $creator->id;
                        $tip->post_id = $post->id;
                        $tip->amount = $price;
                        $tip->creator_amount = $creator_amount;
                        $tip->admin_amount = $fee_amount;
                        $tip->gateway = 'PayStack';
                        $tip->save();

                        // notify creator by email and on site
                        $creator->notify(new TipReceivedNotification($tip));

                    // process message unlock
                    }elseif(isset($metadata->message_id)) {

                        // find this message
                        $message = Message::findOrFail($metadata->message_id);

                        // set creator | subscriber
                        $creator = User::findOrFail($message->from_id);
                        $subscriber = User::findOrFail($message->to_id);

                        // compute platform fee
                        // $platform_fee = opt('payment-settings.site_fee');
                        $platform_fee = $creator->userFee;
                        $fee_amount = ($price * $platform_fee) / 100;

                        // compute creator amount
                        $creator_amount = number_format($price - $fee_amount, 2);
                        
                        // update creator balance
                        $creator->balance += $creator_amount;
                        $creator->save();

                        // create unlock
                        $tip = new Unlock;
                        $tip->amount = $price;
                        $tip->creator_amount = $creator_amount;
                        $tip->admin_amount = $fee_amount;
                        $tip->tipper_id = $subscriber->id;
                        $tip->creator_id = $creator->id;
                        $tip->message_id  = $message->id;
                        $tip->gateway = 'PayStack';
                        $tip->save();

                        // notify creator by email and on site
                        $creator->notify(new UnlockedMessageNotification($tip));

                    // PROCESS SUBSCRIPTION RENEWAL
                    }elseif(isset($data->plan)) {

                        // get transaction reference
                        $invRef = $data->reference;

                        // get invoice by reference
                        $i = Invoice::where('invoice_id', $invRef)->firstOrFail();

                        // update invoice to paid
                        $i->payment_status = 'Paid';
                        $i->save();

                        // update subscription expiry date
                        $subscription = $i->subscription;
                        $subscription->subscription_expires = Carbon::now()->addMonths(1);
                        $subscription->save();

                    }

                    

                    break;
            }

        } catch(\Exception $e) {
            \Log::info('PayStack Switch Exception: ' . $e->getMessage());
            return response($e->getMessage());
        }

        return response('PayStack Webhook Handled');
        

    }

    // link
    public function link(Request $r)
    {
		setopt('paystack-mode', 'linked');
		return 'Linked';
    }
}

<?php

namespace App\Http\Controllers;

use App\Post;
use App\Tips;
use App\User;
use App\Unlock;
use App\Invoice;
use App\Message;
use App\Subscription;
use Illuminate\Http\Request;
use Transbank\Webpay\Options;
use Transbank\Webpay\WebpayPlus;
use App\Notifications\TipReceivedNotification;
use App\Notifications\NewSubscriberNotification;
use App\Notifications\UnlockedMessageNotification;

class WebpayPlusController extends Controller
{
    public function __construct(){

        $this->middleware('auth');

        if (opt('TransBank_ENV', 'Testing') == 'Production') {
            
            WebpayPlus::setCommerceCode(opt('TransBank_CC', 123));
            WebpayPlus::setApiKey(opt('TransBank_Key', 123));
            WebpayPlus::setIntegrationType('LIVE');
            
        } elseif(opt('TransBank_ENV', 'Testing') == 'Testing') {

            WebpayPlus::configureForTesting();

        }

    }

    // redirect user to payment (TIPS)
    public function createdTransaction($post, $creator, $tipper, $amount)
    {

        try {

            // get post
            $post = Post::findOrFail($post);

            // get creator
            $creator = User::findOrFail($creator);

            // get platform fee
            // $platform_fee = opt('payment-settings.site_fee');
            $platform_fee = $creator->userFee;
            $fee_amount = ($amount * $platform_fee) / 100;

            // compute creator amount
            $creator_amount = number_format($amount - $fee_amount, 2);

            // create tip
            $tip = new Tips;
            $tip->amount = $amount;
            $tip->creator_amount = $creator_amount;
            $tip->admin_amount = $fee_amount;
            $tip->tipper_id = $tipper;
            $tip->creator_id = $creator->id;
            $tip->post_id = $post->id;
            $tip->gateway = 'TransBank';
            $tip->payment_status = 'Pending';
            $tip->save();

            // generate uniqueId
            $uniqueId = uniqid();

            // put into session
            session(['uniqueId' => $uniqueId]);
            
            // Get Token and Redirect URL
            $resp = WebpayPlus\Transaction::create($tip->id, $uniqueId, $amount, route('wpb-return-url'));

            // Put Token into Session
            session(['token' => $resp->getToken()]);

            return view('tips.transbank', compact('resp', 'creator', 'amount', 'post'));

        }catch(\Exception $e) {
            dd('TransBank Returned this error: ' . $e->getMessage());
        }

    }

    // process TIP
    public function commitTransaction(Request $request)
    {

        if(!$request->has('token_ws')) {
            alert()->info('Canceled with token: ' . session('token'));
            return redirect(route('feed') . '?token=' . session('token'));
        }

        // get req data
        $req = $request->except('_token');

        // commit transaction
        $resp = WebpayPlus\Transaction::commit($req["token_ws"]);

        // get tip id
        $tipId = $resp->buyOrder;

        // find this tip
        $tip = Tips::findOrFail($tipId);

        if($resp->status == 'AUTHORIZED') {

            // avoid double processing
            if ($tip->payment_status != 'Paid') {

                $tip->payment_status = 'Paid';
                $tip->save();

                // update user balance
                $u = $tip->tipped;
                $u->balance += $tip->creator_amount;
                $u->save();

                // send email notification
                $u->notify(new TipReceivedNotification($tip));

            }

            alert()->info(__('general.tipSuccess'));

        }else{

            // remove tip from database
            // $tip->delete();

            alert()->error(__('v18.tipRejected', ['gateway_status' => $resp->status]));
        }

        $url = route('single-post', ['post' => $tip->post_id]);
        return view('subscribe.transbank-return', compact('resp', 'tip', 'url'));

    }

    // redirect user to payment (MESSAGE UNLOCKS)
    public function createUnlockTx(Message $message, $lockPrice)
    {

        try {

             // get amount
             $amount = $lockPrice;
             $price  = $lockPrice;

             // set creator | subscriber
             $creator = User::findOrFail($message->from_id);
             $subscriber = User::findOrFail($message->to_id);

             // compute platform fee
			// $platform_fee = opt('payment-settings.site_fee');
             $platform_fee = $creator->userFee;
             $fee_amount = ($price * $platform_fee) / 100;

             // compute creator amount
             $creator_amount = number_format($price - $fee_amount, 2);

             // create unlock
             $tip = new Unlock;
             $tip->amount = $price;
             $tip->creator_amount = $creator_amount;
             $tip->admin_amount = $fee_amount;
             $tip->tipper_id = $subscriber->id;
             $tip->creator_id = $creator->id;
             $tip->message_id  = $message->id;
             $tip->gateway = 'TransBank';
             $tip->payment_status = 'Pending';
             $tip->save();

            // generate uniqueId
            $uniqueId = uniqid();

            // put into session
            session(['uniqueId' => $uniqueId]);
            
            // Get Token and Redirect URL
            $resp = WebpayPlus\Transaction::create($tip->id, $uniqueId, $amount, route('wpb-return-tx'));

            // Put Token into Session
            session(['token' => $resp->getToken()]);

            return view('messages.transbank-unlock', compact('message', 'lockPrice', 'resp'));

        }catch(\Exception $e) {
            dd('TransBank Returned this error: ' . $e->getMessage());
        }

    }

    // process UNLOCK
    public function commitUnlockTransaction(Request $request)
    {

        if(!$request->has('token_ws')) {
            alert()->info('Canceled with token: ' . session('token'));
            return redirect(route('feed') . '?token=' . session('token'));
        }

        // get req data
        $req = $request->except('_token');

        // commit transaction
        $resp = WebpayPlus\Transaction::commit($req["token_ws"]);

        // get tip id
        $tipId = $resp->buyOrder;

        // find this tip
        $tip = Unlock::findOrFail($tipId);

        if($resp->status == 'AUTHORIZED') {

            // avoid double processing
            if ($tip->payment_status != 'Paid') {

                $tip->payment_status = 'Paid';
                $tip->save();

                // update user balance
                $u = $tip->tipped;
                $u->balance += $tip->creator_amount;
                $u->save();

                // send email notification
                $u->notify(new UnlockedMessageNotification($tip));

            }

            alert()->info(__('v19.unlockSuccess'));

        }else{
            alert()->error(__('v18.tipRejected', ['gateway_status' => $resp->status]));
        }

        $url = route('messages.inbox');

        return view('subscribe.transbank-return', compact('resp', 'tip', 'url'));

    }

    // redirect user to payment (Subscriptions)
    public function create_subscription(User $user)
    {
        // check subscription to self
        if (auth()->id() == $user->id) {
			alert()->info(__('general.dontSubscribeToYourself'));
			return back();
        }

        try {

            // compute price
            $price = number_format($user->profile->finalPrice, 0);
            $price = str_replace([',', '.'], ['', ''], $price);

			// get platform fee
			// $platform_fee = opt('payment-settings.site_fee');
            $platform_fee = $user->userFee;

			$fee_amount = ($price * $platform_fee) / 100;

			// compute creator amount
            $creator_amount = number_format($price - $fee_amount, 2);
            
            // compute subscription id
            $subscrId = uniqid();

			// save this order in database
			$subPlan = new Subscription;
			$subPlan->creator_id = $user->id;
			$subPlan->subscriber_id = auth()->user()->id;
			$subPlan->subscription_id = $subscrId;
			$subPlan->gateway = 'TransBank';
			$subPlan->subscription_date = now();
			$subPlan->subscription_expires = now();
			$subPlan->subscription_price = $price;
			$subPlan->creator_amount = $creator_amount;
            $subPlan->admin_amount = $fee_amount;
            $subPlan->save();

            // put into session
            session(['uniqueId' => $subscrId]);
            
            // Get Token and Redirect URL
            $resp = WebpayPlus\Transaction::create($subPlan->id, $subscrId, $price, route('wpb-process-subscription'));

            // Put Token into Session
            session(['token' => $resp->getToken()]);

            return view('subscribe.transbank', compact('user', 'resp'));

        }catch(\Exception $e) {
            dd('TransBank Returned this error: ' . $e->getMessage());
        }

    }

    // process subscription
    public function process_subscription(Request $request)
    {
     
        // get req data
        $req = $request->except('_token');

        if(!$request->has('token_ws')) {
            alert()->info('Canceled with token: ' . session('token'));
            return redirect(route('feed') . '?token=' . session('token'));
        }

        // commit transaction
        $resp = WebpayPlus\Transaction::commit($req["token_ws"]);

        // get tip id
        $sId = $resp->buyOrder;

        // find subscription
        $subscription = Subscription::findOrfail($sId);

        try {

            if($resp->status == 'AUTHORIZED') {

                // update expires
                $subscription->subscription_expires = now()->addMonths(1);
                $subscription->save();

                // notify creator on site & email
                $creator = $subscription->creator;
                $creator->notify(new NewSubscriberNotification($subscription->subscriber));

                // update creator balance
                $creator->balance += $subscription->creator_amount;
                $creator->save();

                // create invoice to be able to have stats in admin
                $i = new Invoice;
                $i->invoice_id = $subscription->subscription_id;
                $i->user_id = $subscription->subscriber_id;
                $i->subscription_id = $subscription->id;
                $i->amount = $subscription->subscription_price;
                $i->payment_status = 'Paid';
                $i->invoice_url = '#';
                $i->save();
                
                alert(__('general.subscriptionProcessing'));
    
            }else{
    
                // remove subscription from database
                $subscription->delete();
    
                alert()->error(__('v18.tipRejected', ['gateway_status' => $resp->status]));
            }

            $tip = $subscription;

            $url = route('feed');
            return view('subscribe.transbank-return', compact('resp', 'tip', 'url'));

        }catch(\Exception $e) {
            dd('TransBank Returned this error: ' . $e->getMessage());
        }

    }
}

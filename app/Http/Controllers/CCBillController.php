<?php

namespace App\Http\Controllers;

use App\Post;
use App\Tips;
use App\User;
use App\Unlock;
use App\Message;
use App\Subscription;
use Illuminate\Http\Request;
use App\Notifications\TipReceivedNotification;
use App\Notifications\NewSubscriberNotification;
use App\Notifications\UnlockedMessageNotification;

class CCBillController extends Controller
{
    // approval
    public function approval(Request $r)
    {
        if($r->post) {

            $post = Post::findOrFail($r->post);

            alert()->info(__('general.tipSuccess'));

            return redirect($post->slug);

        }

        alert()->info(__('v15.you-have-accessed-this-page-in-error'));
        
        return redirect(route('home'));
    }

    // handle CCBill webhooks
    public function webhooks(Request $r)
    {   


        // validate event type
        $this->validate($r, [
            'eventType' => 'required',
            'eventGroupType' => 'required',
            'subscriptionId' => 'required',
            'subscriptionInitialPrice' => 'required',
            'dynamicPricingValidationDigest' => 'required',
         ]);

        // set ccbill currency codes
        $ccbillCurrencyCodes = [];
        $ccbillCurrencyCodes["USD"] = 840;
        $ccbillCurrencyCodes["EUR"] = 978;
        $ccbillCurrencyCodes["AUD"] = 036;
        $ccbillCurrencyCodes["CAD"] = 124;
        $ccbillCurrencyCodes["GBP"] = 826;
        $ccbillCurrencyCodes["JPY"] = 392;

        // set amount
        $amount = $r->subscriptionInitialPrice;
         
        // get the hash
        $digest = $r->dynamicPricingValidationDigest;

        // get site currencies
        $siteCurrency = strtoupper(opt('payment-settings.currency_code', 'USD'));

        // do we have this site currency on CCBill as well? if not, default to USD
        if( isset($ccbillCurrencyCodes[$siteCurrency]) )
            $currencyCode = $ccbillCurrencyCodes[$siteCurrency];
        else
            $currencyCode = 840;

        // get salt
        $salt = opt('ccbill_salt');
        
        // if tip
        if ($r->rebills == 0) {

            // set type = 'Tip'
            $type = 'Tip';
            
            // set initial period
            $initialPeriod = 365;

            // generate hash: formPrice, formPeriod, currencyCode, salt
            $hash = md5($amount . $initialPeriod . $currencyCode . $salt);


        }else{

            // set type = 'Subscription'
            $type = 'Subscription';

            // set initial period
            $initialPeriod = 30;

            // set infinite rebills
            $numRebills = 99;

            // generate hash: initialPrice, initialPeriod, recurringPrice, recurringPeriod, numRebills, currencyCode, salt
            $hash = md5($amount . $initialPeriod . $amount . $initialPeriod . $numRebills . $currencyCode . $salt);

        }

        // validate the hash
        if( $hash != $digest ) {

            \Log::info('CCBILL WEBHOOKS HASH MISMATCH:');
            \Log::info('RECEIVED HASH: ' . $digest);
            \Log::info('OUR HASH: ' . $hash);
            \Log::info('FULL REQUEST BELOW');
            \Log::info($r->all());

            // stop here.
            return response('HASH MISMATCH, TRANSACTION REJECTED');

        }

        // validate fields depending on type
        if( $type == 'Tip' ) {

            // if it's a message unlock
            if (!$r->has('message_id')) {
                $validateFields = [
                    'X-tipper' => 'required|numeric',
                    'X-creator' => 'required|numeric',
                    'X-post' => 'required|numeric'
                ];
            }

        }elseif( $type == 'Subscription' ) {

            $validateFields = [
                'X-subscriber' => 'required|numeric',
                'X-creator' => 'required|numeric',
            ];

        }else{

            $notRecognized = 'Type not recognized: ' . $type;

            \Log::info($notRecognized);
            return response($notRecognized);

        }


        // validate fields depending on payment type Subscription|Tip
        $this->validate($r, [
            $validateFields    
        ]);

        // get event type
        switch($r->eventType) {
            
            case 'NewSaleSuccess':

                    // process new tip/subscription
                    if($type == 'Tip' && !$r->has('message_id'))
                        $this->_processNewTip($r);
                    elseif($type == 'Tip' && $r->has('message_id'))
                        $this->_processNewUnlock($r);
                    elseif($type == 'Subscription')
                        $this->_processNewSubscription($r);

                break;

            case 'RenewalSuccess':

                // process existent subscription renewal
                $this->_processSubscriptionRenewal($r);

                break;
            

        }
        

        return response('CCBill WebHook Handled');
    }

    // process new tip
    public function _processNewTip($r) {

        // find this post
        $post = Post::findOrFail($r->{'X-post'});

        // find this creator
        $creator = User::findOrFail($r->{'X-creator'});

        // find this subscriber
        $subscriber = User::findOrFail($r->{'X-tipper'});

        // compute price
        $price = $r->subscriptionInitialPrice;

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
        $tip->amount = $price;
        $tip->creator_amount = $creator_amount;
        $tip->admin_amount = $fee_amount;
        $tip->tipper_id = $subscriber->id;
        $tip->creator_id = $creator->id;
        $tip->post_id = $post->id;
        $tip->gateway = 'CCBill';
        $tip->save();

        // notify creator by email and on site
        $creator->notify(new TipReceivedNotification($tip));

    }

    // process message unlock
    public function _processNewUnlock($r) {

        // find this message
        $message = Message::findOrFail($r->{'X-message_id'});

        // compute price
        $price = $r->subscriptionInitialPrice;


        // set creator | subscriber
        $creator = User::findOrFail($message->from_id);
        $subscriber = User::findOrFail($message->to_id);

        // get platform fee
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
        $tip->gateway = 'CCBill';
        $tip->save();

        // notify creator by email and on site
        $creator->notify(new UnlockedMessageNotification($tip));

    }

    // process new subscription
    public function _processNewSubscription($r) {

        // find this creator
        $creator = User::findOrFail($r->{'X-creator'});

        // find this subscriber
        $subscriber = User::findOrFail($r->{'X-subscriber'});

        // compute price
        $price = $r->subscriptionInitialPrice;

        // get platform fee
        // $platform_fee = opt('payment-settings.site_fee');
        $platform_fee = $creator->userFee;
        $fee_amount = ($price * $platform_fee) / 100;

        // compute creator amount
        $creator_amount = number_format($price - $fee_amount, 2);
        
        // update creator balance
        $creator->balance += $creator_amount;
        $creator->save();

        // now that we validated everything, let's insert the subscription in database and notify the creator
        $subPlan = new Subscription;
        $subPlan->creator_id = $creator->id;
        $subPlan->subscriber_id = $subscriber->id;
        $subPlan->subscription_id = $r->subscriptionId;
        $subPlan->gateway = 'CCBill';
        $subPlan->subscription_date = now();
        $subPlan->subscription_expires = now()->addDays(30);
        $subPlan->subscription_price = $price;
        $subPlan->creator_amount = $creator_amount;
        $subPlan->admin_amount = $fee_amount;
        $subPlan->save();

        // notify creator on site & email
        $creator->notify(new NewSubscriberNotification($subscriber));

    }

    // process subscription renewal
    public function _processSubscriptionRenewal($r) {

        // find this creator
        $creator = User::findOrFail($r->{'X-creator'});

        // find this subscriber
        $subscriber = User::findOrFail($r->{'X-subscriber'});

        // compute price
        $price = $r->subscriptionInitialPrice;

        // get platform fee
        // $platform_fee = opt('payment-settings.site_fee');
        $platform_fee = $creator->userFee;
        $fee_amount = ($price * $platform_fee) / 100;

        // compute creator amount
        $creator_amount = number_format($price - $fee_amount, 2);
        
        // update creator balance
        $creator->balance += $creator_amount;
        $creator->save();

        // now that we validated everything, let's update the subscription in database and notify the creator
        $subPlan = Subscription::where('subscription_id', $r->subscriptionId)->firstOrFail();
        $subPlan->subscription_expires = now()->addDays(30);
        $subPlan->save();

        // notify creator on site & email
        $creator->notify(new NewSubscriberNotification($subscriber));

    }
}

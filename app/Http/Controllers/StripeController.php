<?php

namespace App\Http\Controllers;

use App\Tips;
use App\User;
use App\Unlock;
use App\Invoice;
use Carbon\Carbon;
use Stripe\Stripe;
use App\Subscription;
use Illuminate\Http\Request;
use Stripe\Customer as StripeCustomer;
use Stripe\SetupIntent as StripeIntent;
use Stripe\StripeClient as StripeClient;
use UxWeb\SweetAlert\SweetAlert as Alert;
use App\Notifications\PaymentActionRequired;
use Stripe\Checkout\Session as StripeSession;
use App\Notifications\InovicePaidNotification;
use App\Notifications\TipReceivedNotification;
use App\Notifications\NewSubscriberNotification;
use App\Notifications\UnlockedMessageNotification;
use App\Notifications\SubscriptionSuccessfullyRenewed;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    // auth middleware
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'stripeHooks']);
    }

    // hande stripe web hooks
    public function stripeHooks(Request $r)
    {
        try {

            // setup stripe
            Stripe::setApiKey(opt('STRIPE_SECRET_KEY', '123'));

            // get endpoint secret
            $endpoint_secret = opt('STRIPE_WEBHOOK_SECRET', '123');

            $payload = @file_get_contents('php://input');
            $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
            $event = null;

            try {
                $event = \Stripe\Webhook::constructEvent(
                    $payload,
                    $sig_header,
                    $endpoint_secret
                );
            } catch (\UnexpectedValueException $e) {
                // Invalid payload
                http_response_code(400);
                exit();
            } catch (\Stripe\Exception\SignatureVerificationException $e) {
                // Invalid signature
                http_response_code(400);
                exit();
            }
            
            Log::info('Stripe Webhooks start');
            Log::info(str_repeat('-', 15));

            Log::info('Event Type: ' . $event->type);
            Log::info($event->data->object);

            Log::info(str_repeat('-', 15));
            Log::info('Stripe Webhooks end');

            // Handle the event
            switch ($event->type) {

                case 'invoice.payment_succeeded':

                    // get invoice
                    $invoice = $event->data->object;

                    // find subscription for this invoice
                    $subscription = Subscription::where('subscription_id', $invoice->subscription)->firstOrFail();
                    $subscription->subscription_expires = Carbon::now()->addMonths(1);
                    $subscription->save();

                    // Update to Paid
                    $i = Invoice::where('invoice_id', $invoice->id)->firstOrFail();
                    $i->payment_status = 'Paid';
                    $i->save();

                    // notify fan on site & email
                    $subscriber = $subscription->subscriber;
                    $subscriber->notify(new InovicePaidNotification($i));

                    // notify creator on site & email
                    $creator = $subscription->creator;
                    $creator->notify(new NewSubscriberNotification($subscriber));

                    // update creator balance
                    $creator->balance += $subscription->creator_amount;
                    $creator->save();

                    break;

                case 'invoice.created':

                    // get invoice 
                    $invoice = $event->data->object;

                    // find subscription for this invoice
                    $subscription = Subscription::where('subscription_id', $invoice->subscription)->firstOrFail();

                    // insert the invoice with payment_status 'Action Required'
                    $i = Invoice::where('invoice_id', $invoice->id)->firstOr(function () use ($invoice, $subscription) {

                        $i = new Invoice;
                        $i->invoice_id = $invoice->id;
                        $i->user_id = $subscription->subscriber_id;
                        $i->subscription_id = $subscription->id;
                        $i->amount = number_format($invoice->total / 100, 2);
                        $i->payment_status = 'Action Required';
                        $i->invoice_url = $invoice->hosted_invoice_url;
                        $i->save();
                    });

                    break;

                case 'invoice.payment_action_required':

                    // get invoice 
                    $invoice = $event->data->object;

                    // find subscription for this invoice
                    $subscription = Subscription::where('subscription_id', $invoice->subscription)
                        ->firstOrFail();

                    $i = Invoice::where('invoice_id', $invoice->id)->firstOr(function () use ($invoice, $subscription) {

                        $i = new Invoice;
                        $i->invoice_id = $invoice->id;
                        $i->user_id = $subscription->subscriber_id;
                        $i->subscription_id = $subscription->id;
                        $i->amount = number_format($invoice->total / 100, 2);
                        $i->payment_status = 'Action Required';
                        $i->invoice_url = $invoice->hosted_invoice_url;
                        $i->save();

                        return $i;
                    });

                    // find fan
                    $fan = $i->subscription->subscriber;

                    // notify user on site & email
                    $fan->notify(new PaymentActionRequired($i));

                    break;

                case 'charge.succeeded':

                    $intent = $event->data->object;

                    // find this tip or unlock
                    $tip = Tips::where('intent', $intent->payment_intent)->first();

                    // if it's not a tip - must be an unlock
                    if (!$tip) {
                        $unlock = Unlock::where('intent', $intent->payment_intent)->firstOrFail();
                        
                        \Log::info('unlock coming for intent=' . $intent->payment_intent);
                        \Log::debug($unlock);

                        // update payment status
                        $unlock->payment_status = 'Paid';
                        $unlock->save();

                        // update user balance
                        $u = $unlock->tipped;
                        $u->balance += $unlock->creator_amount;
                        $u->save();

                        // send email notification
                        $u->notify(new UnlockedMessageNotification($unlock));

                    }else{

                        \Log::info('tip coming for intent=' . $intent->payment_intent);
                        \Log::debug($tip);

                        // update payment status
                        $tip->payment_status = 'Paid';
                        $tip->save();

                        // update user balance
                        $u = $tip->tipped;
                        $u->balance += $tip->creator_amount;
                        $u->save();

                        // send email notification
                        $u->notify(new TipReceivedNotification($tip));

                    }

                    break;

                default:
                    return response('Received unknown event type ' . $event->type);
            }

            return response('Webhook 200');
        } catch (\Exception $e) {
            \Log::debug('STRIPE HOOK EXCEPTION');
            \Log::debug($e->getMessage());
            \Log::debug($e);
        }
    }

    // stripe add credit card payment
    public function addStripeCard()
    {

        try {
            // set stripe secret
            Stripe::setApiKey(opt('STRIPE_SECRET_KEY', '123'));

            // get stripe client
            $stripe = new StripeClient(opt('STRIPE_SECRET_KEY', '123'));

            // set stripe customer id
            $customerId = 'fan_' . auth()->id();

            // does customer already exist?
            try {
                $customer = StripeCustomer::retrieve(
                    $customerId,
                    [
                        'expand' => [
                            'invoice_settings.default_payment_method',
                            'default_source',
                        ],
                    ],
                );
            } catch (\Exception $e) {
                $customer = StripeCustomer::create([
                    'id'    => $customerId,
                    'email' => auth()->user()->email,
                    'name'  => auth()->user()->name,
                ]);
            }

            // get session
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'mode' => 'setup',
                'customer' => $customer->id,
                'success_url' => route('captureStripeCard'),
                'cancel_url' => route('addStripeCard'),
            ]);

            // stripe session
            $stripeSession = $session->id;

            // set session
            session(['stripeSession' => $stripeSession]);

            return view('billing.stripe-add-card', compact('stripeSession'));
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function captureStripeCard()
    {
        // if we don't have stripe session, abort
        if (!session('stripeSession'))
            abort(403);

        try {
            // set stripe secret
            Stripe::setApiKey(opt('STRIPE_SECRET_KEY', '123'));

            // get session
            $session = StripeSession::retrieve(session('stripeSession'));

            // get setup intent
            $intent = StripeIntent::retrieve($session->setup_intent);

            // attach payment method to customer
            $stripe = new StripeClient(opt('STRIPE_SECRET_KEY', '123'));

            $attach = $stripe->paymentMethods->attach(
                $intent->payment_method,
                [
                    'customer' => 'fan_' . auth()->id(),
                ],
            );

            // update user default payment method
            $updateDefaultPm = $stripe->customers->update('fan_' . auth()->id(), [
                'invoice_settings' => [
                    'default_payment_method' => $intent->payment_method
                ]
            ]);

            // payment method details
            $pmdetails = $stripe->paymentMethods->retrieve(
                $intent->payment_method,
                []
            );

            // get card
            $card = $pmdetails->card;

            // build pmeta
            $pmeta = [
                'intent'         => $intent->id,
                'payment_method' => $intent->payment_method,
                'ending'          => $card->last4,
                'expiry' => $card->exp_month . '/' . $card->exp_year,
                'brand' => $card->brand,
            ];

            // reset other payment cards
            auth()->user()->paymentMethods()->update(['is_default' => 'No']);

            // attach payment method to this user
            auth()->user()->paymentMethods()->create([
                'gateway'       => 'Stripe',
                'p_meta'        => $pmeta,
                'is_default'    => 'Yes',
            ]);


            Alert::info(__('general.cardSuccessfullyAdded'));

            return redirect(route('billing.cards'));
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}

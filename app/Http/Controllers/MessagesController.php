<?php

namespace App\Http\Controllers;

use App\Tips;
use App\User;
use App\Unlock;
use App\Message;
use Carbon\Carbon;
use App\MessageMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Notifications\UnlockedMessageNotification;
use finfo;

class MessagesController extends Controller
{
    // auth middleware
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['processPayPalTip', 'coinPaymentsUnlockIPN']]);
    }

    public function inbox()
    {
        // get this users messages
        return view('messages.inbox');
    }

    // get recipients for user
    public function getRecipientsForUser()
    {
        
        // get followers and follows with total messages
        $people = auth()->user()
            ->whereHas('followings', function ($q) {
                $q->where('following_id', auth()->id());
            })
            ->orWhereHas('followers', function ($q) {
                $q->where('follower_id', auth()->id());
            })
            ->get();

        // get last messages towards authenticated user
        $unreadMsg = Message::select('message', 'from_id', 'is_read')
            ->where('to_id', auth()->id())
            ->with('media')
            ->orderByDesc('id')
            ->get()
            ->unique('from_id');


        $people->transform(function($recipient) use($unreadMsg) {


            if ($message = $unreadMsg->where('from_id', $recipient->id)->first()) {

                $message->message = substr($message->message, 0, 55) . '..';

                $recipient->last_message = $message;
                
            }else{
                $recipient->last_message = null;
            }

            return $recipient;

        });
       

        return response()->json($people);
    }

    // get messages with this user
    public function conversation($user) {

//        DB::enableQueryLog(); // Enable query log
        // auth user unlocks
        $authUnlocks = Unlock::where('tipper_id', auth()->id())->get()->pluck('message_id')->toArray();
        $authMsgIds = Message::where('from_id', auth()->id())->get()->pluck('id')->toArray();
        $accessIds = array_unique(array_merge($authUnlocks, $authMsgIds));


        // fetch messages
        $messages = Message::where(function ($q) use ($user) {
            $q->where('to_id', auth()->id());
            $q->where('from_id', $user);
        })->orWhere(function ($q) use ($user) {
            $q->where('from_id', auth()->id());
            $q->where('to_id', $user);
        })
            ->with('receiver:id,name', 'sender:id,name')
            ->with('media')
            ->orderBy('created_at')
            ->get();

        // append time ago
        $messages->each->append('timeAgo');

        // transform
        $messages->transform(function($msg)  use($accessIds) {

             // only allow access to media_content if this user has access to it
             $m = &$msg;
             $m->message = wordwrap($m->message, 26, "\n", true);

             if(is_countable($m->media) && count($m->media)) {
                 foreach($m->media as $media) {
                     // spoof the link if user doesn't have access
                     if($media->lock_type != 'Free' && !in_array($media->message_id, $accessIds)) {
                         $media->media_content = 'NEEDS UNLOCKING';
                     }else{

                        $media->lock_type = "Free";

                         // append cloud storage url
                         if( $media->disk == 'backblaze' ) {
                             $media->media_content = 'https://'. opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $media->media_content;
                        }else{
                            $media->media_content = Storage::disk($media->disk)->url($media->media_content);
                        }
                     }
                 }
             }

             return $m;
        });



        // mark as read
        foreach($messages as $m) {
            if($m->from_id != auth()->id()) {
                $m->is_read = 'Yes';
                $m->save();
            }
        }

//        dd(DB::getQueryLog()); // Show results of log


        return response()->json($messages);

    }

    public function getMedia($media)
    {

        $message = MessageMedia::findOrFail($media);

        return view('messages.message-media', compact('message'));

    }

    // returns server time
    public function getServerTime()
    {
        return response()->json(['time' => gmdate("M d Y H:i:s")]);
    }

    // get auth user
    public function getAuthUser()
    {
        $userID = auth()->id();


        $user = User::where('id', $userID)
                        ->with(['messageUnlocks', 'profile'])
                        ->withCount('paymentMethods')
                        ->firstOrFail();


        return $user;
    }

    // send a message
    public function sendMessage(Request $r)
    {

        // validate message
        $v = Validator::make($r->all(), ['message' => 'required|min:1']);

        if($v->fails()) {
            throw new \Exception($v->errors()->first());
        }

        // validate min amount if lockType = Paid
        if($r->price != 0) {

            $min = auth()->user()->profile->minTip;
            $max = opt('maxTipAmount', 999.00);

            if (!$min)
                $min = opt('minTipAmount', 1.99);

            $v = Validator::make($r->all(), ['price' => 'required|numeric|between:' . $min . ',' . $max]);

            if($v->fails()) {
                throw new \Exception($v->errors()->first());
            }
        }
        
        // add the new msg to db
        $msg = new Message;
        $msg->from_id = auth()->id();
        $msg->to_id = $r->toUserId;
        $msg->message = $r->message;
        $msg->save();

        return response()->json(['message' => $msg]);

    }

    // attach media to messages
    public function attachMedia($message, Request  $r)
    {

        // validate initial params
        $this->validate($r, [
            'is_last' => 'required',
            'file' => 'required|file',
            'messageID' => 'required|numeric',
            'attachmentType' => 'required|in:Video,Audio,ZIP,Image',
            'price' => 'required'
        ]);

       // validate message id
        if($message != $r->messageID) {
            throw new \Exception('Invalid messageID');
        }

        // set file type
        $fileType = $r->attachmentType;
        
        // set file
        $file = $r->file('file');

        // temp chunks path
        $path = Storage::disk('local')->path("chunks/{$file->getClientOriginalName()}");

        // filename without .part in it
        $withoutPart = basename($path, '.part');

        // set file name inside path without .part
        $renamePath = public_path('uploads/chunks/' . $withoutPart);

        // set allowed extensions
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'mp3', 'ogg', 'wav', 'mp4', 'webm', 'mov', 'qt', 'zip'];
        $fileExt = explode('.', $withoutPart);
        $fileExt = end($fileExt);
        $fileExt = strtolower($fileExt);

        // preliminary: validate allowed extensions
        // we're validating true mime later, but just to avoid the effort if fails from the begining
        if(!in_array($fileExt, $allowedExt)) {

            FileFacade::delete($renamePath);

            throw new \Exception('Invalid extension');
        }

        // build allowed mimes
        $allowedMimes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 

                         'audio/mp3', 'audio/ogg', 'audio/wav', 'audio/mpeg',

                         'video/mp4', 'video/webm', 'video/mov', 'video/ogg', 'video/qt', 'video/quicktime',

                         'application/zip'];

        // append chunk to the file
        FileFacade::append($path, $file->get());

        // finally, let's make the file complete
        if ($r->boolean('is_last')) {

            // rename the file to original name
            FileFacade::move($path, $renamePath);

            // get mime of the file
            try {

                // set a ref to local file
                $localFile = new File($renamePath);
                
                try {

                    // first, lets get the mime type
                    $finfo = new finfo;
                    $mime = $finfo->file($renamePath, FILEINFO_MIME_TYPE);

                }catch(\Exception $e) {

                    $mime = null;

                }


                // validate allowed mimes
                if ($mime) {

                    if (!in_array($mime, $allowedMimes) && $mime != 'application/octet-stream') {
                        throw new \Exception('Invalid file type: ' . $mime);
                    }

                    // this is from chunks, keep it as it passed the other validation
                    if($mime == 'application/octet-stream') {
                        $mime = $fileType;
                    }

                }else{
                    $mime = $fileType;
                }


                // set file destination
                switch($mime) {
                    case stristr($mime, 'image') !== false:
                        $fileDestination = 'userPics';
                        $mediaType = 'Image';
                    break;
                    case stristr($mime, 'video') !== false:
                        $fileDestination = 'userVids';
                        $mediaType = 'Video';
                    break;
                    case stristr($mime, 'audio') !== false:
                        $fileDestination = 'userAudio';
                        $mediaType = 'Audio';
                    break;
                    case stristr($mime, 'zip') !== false:
                        $fileDestination = 'userZips';
                        $mediaType = 'ZIP';
                    break;
                    default:
                        $fileDestination = 'None';
                            break;
                }


                // Move this thing
                $fileName = Storage::disk(env('DEFAULT_STORAGE'))->putFile($fileDestination, $localFile, 'public');

                // remove it from chunks folder
                FileFacade::delete($renamePath);

                // compute lock type
                $lockType = 'Free';
                if($r->price > 0) {
                    $lockType = 'Paid';
                }

                // insert message media
                $media = new MessageMedia;
                $media->message_id = $r->messageID;
                $media->media_content = $fileName;
                $media->media_type = $mediaType;
                $media->disk = env('DEFAULT_STORAGE');
                $media->lock_type = $lockType;
                $media->lock_price = $r->price;
                $media->save();
                

            }catch(\Exception $e) {

                FileFacade::delete($renamePath);
                
                throw new \Exception('Error: ' . $e->getMessage());
            }


        }

        return response()->json(['success' => true]);
    }

    // download zip
    public function downloadZip(MessageMedia $messageMedia)
    {
        

        if( $messageMedia->lock_type == 'Free' OR auth()->id() == $messageMedia->message->from_id OR Unlock::where('message_id', $messageMedia->message_id)->where('payment_status', 'Paid')->where('tipper_id', auth()->id())->exists() ) {

            if($messageMedia->disk == 'backblaze')
                $redirectTo = 'https://'. opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $messageMedia->media_content;
            else
                $redirectTo = Storage::disk($messageMedia->disk)->url($messageMedia->media_content);

            return redirect( $redirectTo );

        }else{
            echo __('v16.accessDenied');
        }

    }

    // unlock message
    public function unlockMessage($gateway, Message $message)
    {

        // get 1st item from the locked media
        $media = $message->media()->where('lock_type', 'Paid')->firstOrFail();


        // get lock price
        $lockPrice = $media->lock_price;
        
        // check gateway and redirect accordingly
        if ($gateway == 'PayPal') {

            return $this->sendToPayPal($message, $lockPrice);

        } elseif ($gateway == 'Card' || $gateway == 'card') {

            if (opt('card_gateway', 'Stripe') == 'Stripe') {
                return $this->stripeUnlock($message, $lockPrice);
            }elseif(opt('card_gateway', 'Stripe') == 'CCBill' ) {
                return $this->ccBillUnlock($message, $lockPrice);
            }elseif(opt('card_gateway', 'Stripe') == 'PayStack' ) {
                return $this->payStackUnlock($message, $lockPrice);
            }elseif(opt('card_gateway', 'Stripe') == 'MercadoPago' ) {
                return $this->mercadoPagoUnlock($message, $lockPrice);
            }elseif(opt('card_gateway', 'Stripe') == 'TransBank' ) {
                return $this->transBankUnlock($message, $lockPrice);
            }elseif(opt('card_gateway', 'Stripe') == 'Crypto' ) {
                return $this->coinPaymentsUnlock($message, $lockPrice);
            }

        }


    }

    // unlock via Stripe
    public function stripeUnlock(Message $message, $lockPrice)
    {
        
        \Stripe\Stripe::setApiKey(opt('STRIPE_SECRET_KEY', 123));

        // get current user
        $user = auth()->user();

        // set stripe customer
        $customer = 'fan_' . auth()->id();

        // set "tipper"
        $tipper = $user;

        // set creator
        $creator = $message->sender;

        // get "tipper" payment methods
        try{
            $pm = $user->paymentMethods()->where('is_default', 'Yes')->firstOrFail();
            $pm = $pm->p_meta;
            $pm_id = $pm['payment_method'];
        }catch(\Exception $e) {
            return redirect(route('addStripeCard'));
        }

        // compute price
        $price = $lockPrice;
        $amount = $lockPrice;

        // get platform fee
        // $platform_fee = opt('payment-settings.site_fee');
        $platform_fee = $creator->userFee;
        $fee_amount = ($price * $platform_fee) / 100;

        // compute creator amount
        $creator_amount = number_format($price - $fee_amount, 2);

        try {

            $intent = \Stripe\PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => opt('payment-settings.currency_code'),
                'customer' => $customer,
                'payment_method' => $pm_id,
                'off_session' => true,
                'confirm' => true,
            ]);

            // update creator balance
            $creator->balance += $creator_amount;
            $creator->save();

            // create unlock payment
            $tip = new Unlock;
            $tip->amount = $amount;;
            $tip->creator_amount = $creator_amount;
            $tip->admin_amount = $fee_amount;
            $tip->tipper_id = $tipper->id;
            $tip->creator_id = $creator->id;
            $tip->message_id  = $message->id;
            $tip->intent = $intent->id;
            $tip->gateway = 'Card';
            $tip->save();

            // notify creator by email and on site
            $creator->notify(new UnlockedMessageNotification($tip));

            alert()->info(__('v19.unlockSuccess'));

        } catch (\Stripe\Exception\CardException $e) {

            $payment_intent_id = $e->getError()->payment_intent->id;
            $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);

            if ($payment_intent->status == 'requires_payment_method') {

                // setup stripe client
                $stripe = new \Stripe\StripeClient(
                    opt('STRIPE_SECRET_KEY', '123')
                );

                // confirm payment
                $confirm = $stripe->paymentIntents->confirm(
                    $payment_intent_id,
                    ['payment_method' => $pm_id],
                );

                 // create unlock payment
                $tip = new Unlock;
                $tip->amount = $amount;;
                $tip->creator_amount = $creator_amount;
                $tip->admin_amount = $fee_amount;
                $tip->tipper_id = $tipper->id;
                $tip->creator_id = $creator->id;
                $tip->message_id  = $message->id;
                $tip->gateway = 'Card';
                $tip->payment_status = 'Pending';
                $tip->intent = $payment_intent_id;
                $tip->save();


                // redirect user to confirmation
                return redirect($confirm->next_action->use_stripe_sdk->stripe_js);
            } else {

                alert()->error($e->getMessage());
            }
        }

        return redirect(route('messages.inbox'));

    }

    // paypal message unlocking
    public function sendToPayPal(Message $message, $lockPrice)
    {
        return view('messages.paypal-unlocking', compact('message', 'lockPrice'));
    }

    // crypto (coinpayments) unlocking
    public function coinPaymentsUnlock(Message $message, $lockPrice)
    {
        return view('messages.coinpayments-crypto-unlock', compact('message', 'lockPrice'));
    }

    // crypto (coinpayments) ipn processing
    public function coinPaymentsUnlockIPN(Message $message, Request $r)
    {
        Log::info('CoinPayments IPN (MSG_UNLOCK): Starting IPN');

        // CoinPayments Credentials Setup
		$cp_merchant_id = opt('COIN_MERCHANT_ID');
    	$cp_ipn_secret = opt('COIN_IPN_SECRET');
    	$cp_debug_email = opt('admin_email');

        // get site currencies
		$siteCurrency = opt('payment-settings.currency_code', 'USD');

		// Set general order info
		$order_currency = $siteCurrency;

		// Validate some stuff
		if (!isset($_POST['ipn_mode']) || $_POST['ipn_mode'] != 'hmac') {
			Log::info('CoinPayments (MSG_UNLOCK): IPN Mode is not HMAC');
			exit;
		}
	
		if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
			Log::info('CoinPayments (MSG_UNLOCK): No HMAC signature sent.');
			exit;
		}
	
		$request = file_get_contents('php://input');
		if ($request === FALSE || empty($request)) {
			Log::info('CoinPayments (MSG_UNLOCK): Error reading POST data');
			exit;
		}
	
		if (!isset($_POST['merchant']) || $_POST['merchant'] != trim($cp_merchant_id)) {
			Log::info('CoinPayments (MSG_UNLOCK): No or incorrect Merchant ID passed');
			exit;
		}
	
		$hmac = hash_hmac("sha512", $request, trim($cp_ipn_secret));
		if (!hash_equals($hmac, $_SERVER['HTTP_HMAC'])) {
			Log::info('CoinPayments (MSG_UNLOCK): HMAC signature does not match');
			exit;
		}

		// HMAC Signature verified at this point, load some variables.
		$ipn_type = @$_POST['ipn_type'];
		$txn_id = @$_POST['txn_id'];
		$item_name = @$_POST['item_name'];
		$amount1 = @floatval($_POST['amount1']);
		$amount2 = @floatval($_POST['amount2']);
		$currency1 = @$_POST['currency1'];
		$currency2 = @$_POST['currency2'];
		$status = @intval($_POST['status']);
		$status_text = @$_POST['status_text'];
        
        // find this creator
        try {
            Log::info('Finding creator');
            $creator = User::findOrFail($message->from_id);
        }catch(\Exception $e) {
            Log::info('CoinPayments (MSG_UNLOCK): Finding creator exception');
            Log::info($e);
        }
        
        // find this subscriber
        try {
            Log::info('Finding subscriber');
            $subscriber = User::findOrFail($message->to_id);
        }catch(\Exception $e) {
            Log::info('CoinPayments (MSG_UNLOCK): Finding subscriber exception');
            Log::info($e);
        }

        // compute price
        $price = $amount1;

        // get platform fee
        // $platform_fee = opt('payment-settings.site_fee');
        $platform_fee = $creator->userFee;
        $fee_amount = ($price * $platform_fee) / 100;

        // compute creator amount
        $creator_amount = number_format($price - $fee_amount, 2);

        if ($ipn_type != 'button') { // Advanced Button payment
			Log::info("CoinPayments (MSG_UNLOCK): IPN OK: Not a button payment");
			exit;
		}
		
		// Check the original currency to make sure the buyer didn't change it.
		if ($currency1 != $order_currency) {
			Log::info('CoinPayments (MSG_UNLOCK): Original currency mismatch!');
			exit;
		}

        // Log Status
		Log::info('CoinPayments (MSG_UNLOCK): Status = ' . $status . ' - [' . $status_text . ']');
	 
        if ($status >= 100 || $status == 2) {
            Log::info('CoinPayments (MSG_UNLOCK): Reached status >= 100 || status == 2');

            try {

                Log::info('CoinPayments (MSG_UNLOCK): Adding unlock in database');

                // create unlock
                $tip = new Unlock;
                $tip->amount = $price;
                $tip->creator_amount = $creator_amount;
                $tip->admin_amount = $fee_amount;
                $tip->tipper_id = $subscriber->id;
                $tip->creator_id = $creator->id;
                $tip->message_id  = $message->id;
                $tip->gateway = 'Crypto';
                $tip->save();

                Log::info('CoinPayments (MSG_UNLOCK): Notifying Creator');
                $creator->notify(new UnlockedMessageNotification($tip));

                Log::info('CoinPayments (MSG_UNLOCK): Updating Creator balance');
                $creator->balance += $creator_amount;
                $creator->save();

            } catch (\Exception $e) {
                Log::info('CoinPayments (MSG_UNLOCK): Error while creating unlock, notifying creator or updating the balance.');
                Log::info($e->getMessage());
                Log::info($e);
            }
        }

        die('IPN_OK');
    }

    // process paypal unlocking
    public function processPayPalUnlocking(Message $message, Request $r)
    {

        // STEP 1: read POST data
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);

        $myPost = array();

        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }

        // read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
        $req = 'cmd=_notify-validate';

        // build req
        foreach ($myPost as $key => $value) {
            $value = urlencode($value);
            $req .= '& ' . trim(strip_tags($key)) . '=' . trim(strip_tags($value));
        }

        // STEP 2: POST IPN data back to PayPal to validate
        // $ch = curl_init('https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
        $ch = curl_init('https://ipnpb.paypal.com/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        // error?
        if (!($res = curl_exec($ch))) {
            \Log::error("Got " . curl_error($ch) . " when processing IPN data (Unlocking Message)");
            curl_close($ch);
            exit;
        } else {
            \Log::info('IPN_POSTED_SUCCESSFULLY (Unlocking Message)');
        }
        curl_close($ch);

        // \Log::info('Result: ' . $res);
        // \Log::debug($r->all());

        // STEP 3: Inspect IPN validation result and act accordingly
        if (strcmp($res, "VERIFIED") == 0) {

            // check that receiver_email is your Primary PayPal email
            $receiver_email = $r->receiver_email;

            if (opt('paypal_email', 'paypal@paypal.com') != $receiver_email) {
                \Log::info('RECEIVER_EMAIL = ' . $receiver_email);
                \Log::info('SHOULD_BE = ' . opt('paypal_email', 'paypal@paypal.com'));
                exit;
            }

            // check if payment status is completed
            if ($r->payment_status != "Completed") {
                \Log::info('Payment status is not Completed but: ' . $r->payment_status);
                exit;
            }

            // find this creator
            $creator = User::findOrFail($message->from_id);

            // find this subscriber
            $subscriber = User::findOrFail($message->to_id);

            // compute price
            $price = $r->mc_gross;

            // get platform fee
            // $platform_fee = opt('payment-settings.site_fee');
            $platform_fee = $creator->userFee;
            $fee_amount = ($price * $platform_fee) / 100;

            // compute creator amount
            $creator_amount = number_format($price - $fee_amount, 2);

            switch ($r->txn_type) {

                case 'web_accept':

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
                    $tip->gateway = 'Card';
                    $tip->save();

                    // notify creator by email and on site
                    $creator->notify(new UnlockedMessageNotification($tip));

                    break;
            }
        } else {
            \Log::info('PayPal Not VERIFIED:' . $res);
        }

    }// ./paypal ipn


    // process PayStack Unlock
    public function payStackUnlock(Message $message, $lockPrice)
    {
        
        // make amount a decimal
        $amount = number_format($lockPrice, 2);

        try {

            // get currency
            $currencyCode = opt('payment-settings.currency_code', 'USD');

            // get user default payment method 
            $pm = auth()->user()->paymentMethods()->where('is_default', 'Yes')->firstOrFail();
            $authCode = $pm->p_meta['authorization_code'];

            // set url
            $url = "https://api.paystack.co/transaction/charge_authorization";

        
            // set fields
            $fields = [
                'email' => auth()->user()->email,
                'amount' => $amount*100,
                'currency' => $currencyCode,
                'authorization_code' => $authCode,
                'metadata' => [ 'message_id' => $message->id ]
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

            // sleep to have time for processing
            sleep(2);

            // add message
            alert()->info(__('v19.unlockProcessing'));

            // redirect to messages inbox
            return redirect(route('messages.inbox'));

        } catch(\Exception $e) {

            alert()->error($e->getMessage());
            return back();
        }


    }

    // process CCBill Unlock
    public function ccBillUnlock(Message $message, $lockPrice)
    {

        // make amount a decimal
        $amount = number_format($lockPrice, 2);

        // set ccbill currency codes
        $ccbillCurrencyCodes = [];
        $ccbillCurrencyCodes["USD"] = 840;
        $ccbillCurrencyCodes["EUR"] = 978;
        $ccbillCurrencyCodes["AUD"] = 036;
        $ccbillCurrencyCodes["CAD"] = 124;
        $ccbillCurrencyCodes["GBP"] = 826;
        $ccbillCurrencyCodes["JPY"] = 392;

        // get site currencies
        $siteCurrency = strtoupper(opt('payment-settings.currency_code', 'USD'));

        // do we have this site currency on CCBill as well? if not, default to USD
        if( isset($ccbillCurrencyCodes[$siteCurrency]) )
            $currencyCode = $ccbillCurrencyCodes[$siteCurrency];
        else
            $currencyCode = 840;

        // get salt
        $salt = opt('ccbill_salt');
        
        // set initial period
        $initialPeriod = 365;

        // generate hash: formPrice, formPeriod, currencyCode, salt
        $hash = md5($amount . $initialPeriod . $currencyCode . $salt);

        // redirect to CCBill payment
        $ccBillParams['clientAccnum'] = opt('ccbill_clientAccnum');
        $ccBillParams['clientSubacc'] = opt('ccbill_Subacc');
        $ccBillParams['currencyCode'] = $currencyCode;
        $ccBillParams['formDigest'] = $hash;
        $ccBillParams['initialPrice'] = $amount;
        $ccBillParams['initialPeriod'] = $initialPeriod;
        $ccBillParams['message_id'] = $message->id;

        // set form id
        $formId = opt('ccbill_flexid');

        // set base url for CCBill Gateway
        $baseURL = 'https://api.ccbill.com/wap-frontflex/flexforms/' . $formId;

        // build redirect url to CCbill Pay
        $urlParams = http_build_query($ccBillParams);
        $redirectUrl = $baseURL . '?' . $urlParams;

        return redirect($redirectUrl);

    }

    // send to TransBank unlock
    public function transBankUnlock(Message $message, $lockPrice)
    {
        return redirect(route('wbp-msg-unlock', ['message' => $message, 'lockPrice' => $lockPrice]));
    }

    // send to mercado pago
    public function mercadoPagoUnlock(Message $message, $lockPrice)
    {

        try {

            // set amount to double
            $amount = number_format($lockPrice, 2);

            // set mercadopago secret key
            \MercadoPago\SDK::setAccessToken(opt('MERCADOPAGO_SECRET_KEY'));
            
            // Create a preference object
            $preference = new \MercadoPago\Preference();

            // Create a preference item
            $item = new \MercadoPago\Item();
            $item->title = __('v19.unlockInfo') . $amount;
            $item->quantity = 1;
            $item->unit_price = $amount;
            $item->currency_id = opt('payment-settings.currency_code', 'USD');

            // append item to preference
            $preference->items = [$item];

            // add auto-return
            $preference->auto_return = 'approved';
            
            // add return url
            $preference->back_urls = [ 'success' => route('mercadoPagoUnlockIPN') ];

            // compute price
            $price = $amount;

            // get platform fee
            // $platform_fee = opt('payment-settings.site_fee');
            $platform_fee = $message->sender->userFee;
            $fee_amount = ($price * $platform_fee) / 100;

            // compute creator amount
            $creator_amount = number_format($price - $fee_amount, 2);

            // get current user
            $user = auth()->user();

            // set "tipper"
            $tipper = $user;

            // set creator
            $creator = $message->sender;
            
            // create unlock payment
            $tip = new Unlock;
            $tip->amount = $amount;;
            $tip->creator_amount = $creator_amount;
            $tip->admin_amount = $fee_amount;
            $tip->tipper_id = $tipper->id;
            $tip->creator_id = $creator->id;
            $tip->message_id  = $message->id;
            $tip->gateway = 'Card';
            $tip->payment_status = 'Pending';
            $tip->save();

            // add tip id into reference
            $preference->external_reference = $tip->id;

            // add tip id into session
            session(['mgpgoUnlockId' => $tip->id]);

            // exclude cash
            $preference->payment_methods = array(
                "excluded_payment_types" => array(
                  array("id" => "cash")
                )
            );

            // binary only
            $preference->binary_mode = true;

            // save
            $preference->save();

            // redirect to payment (live)
            return redirect($preference->init_point);

        } catch(\Exception $e) {
            dd($e->getMessage());
        }

    }

    // process MercadoPago Unlock
    public function mercadoPagoUnlockProcess(Request $r)
    {

        try {

            // if payment not approved
            if($r->status != 'approved') {
                throw new \Exception('Payment failed');
            }

            // get session tip id
            if( session()->has('mgpgoUnlockId') ) {

                // set tip id
                $tipId = session('mgpgoUnlockId');

                // delete from session
                session()->forget('mgpgoUnlockId');

            }elseif( $r->has('external_reference') ){

                // set tip id
                $tipId = $r->external_reference;

            }

            // find this tip
            $tip = Unlock::findOrFail($tipId);

            // update payment status
            $tip->payment_status = 'Paid';
            $tip->save();

            // set creator
            $creator = $tip->tipped;

            // update creator balance
            $creator->balance += $tip->creator_amount;
            $creator->save();

            // notify creator by email and on site
            $creator->notify(new UnlockedMessageNotification($tip));

            // add message
            alert()->info(__('v19.unlockProcessing'));

            // redirect to messages inbox
            return redirect(route('messages.inbox'));

        }catch(\Exception $e) {

            alert()->error($e->getMessage());
            return redirect('messages.inbox');
        }
        

    }


}

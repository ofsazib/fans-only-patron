<?php

namespace App\Http\Controllers;

use Alert;
use App\User;
use App\Profile;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\VerificationRequestedEmail;

class ProfileController extends Controller
{
    // auth middleware
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['showProfile', 'ajaxFeedForProfile']]);
    }

    // GET /{username}
    // @route( 'profile.show' )
    public function showProfile($username)
    {
        // reset lastId if we have it
        if(Session::has('lastId')) {
            Session::forget('lastId');
        }

        // redirect to login if setting says No
        if(opt('allow_guest_profile_view', 'Yes') == 'No' && !auth()->check())
            return redirect(route('login'));

        // profile fields
        $profileFields = [
            'id', 'name', 'user_id', 'username', 'creating', 'profilePic',
            'coverPic', 'isVerified', 'isFeatured',
            'fbUrl', 'twUrl', 'ytUrl', 'twUrl', 'ytUrl', 'twitchUrl', 'instaUrl',
            'monthlyFee', 'minTip', 'discountedFee'
        ];

        // find this user profile
        $profile = Profile::where('username', $username)
            ->withCount(['fans' => function ($q) {
                $q->where('subscriptions.subscription_expires', '>=', now());
            }])
            ->firstOrFail();

        // find posts for this handle
        $feed = $profile->posts()->where('pinned', 'no')
            ->with(['profile' => function ($q) use ($profileFields) {
                $q->select($profileFields);
            }])
            ->orderBy('posts.id', 'DESC')
            ->take(opt('feedPerPage', 10))
            ->get();

        $pinned = $profile->posts()->where('pinned', 'yes')
        ->with(['profile' => function ($q) use ($profileFields) {
            $q->select($profileFields);
        }])
        ->orderBy('posts.id', 'DESC')
        ->get(); 


        return view('profile/user-profile', compact('profile', 'feed', 'pinned'));
    }

    public function ajaxFeedForProfile(Profile $profile, $lastId = null)
    {
        // define last id from session if we have it
        if( Session::has('lastId') ) {
            $lastId = Session::get('lastId');
        }

        // find posts for this handle
        $feed = $profile->posts()->where('pinned', 'no')
            ->with('profile')
            ->orderBy('posts.id', 'DESC')
            ->take(opt('feedPerPage', 10));

        if (!is_null($lastId))
            $feed->where('id', '<', $lastId);

        $feed = $feed->get();

        if (!$feed->count()) {

            return response()->json(['view' => '', 'lastId' => 0]);
        }

        // compute the view
        $view = view('posts.ajax-feed', compact('feed'));

        // compute last id
        $lastId = $feed->last()->id;

        // update the session with the last id
        Session::put('lastId', $lastId);

        return response()->json(['view' => $view->render(), 'lastId' => Session::get('lastId')]);
    }

    // GET /my-profile
    // @route( 'startMyPage' )
    public function create()
    {

        // get categories
        $category = '';

        // get this user profile if it exists
        $p = auth()->user()->profile;

        // get all categories
        $categories = [];

        // set active
        $active = 'my-profile';

        // set user category
        $userCategory = $p->category->id;

        return view('profile.create', compact('categories', 'p', 'userCategory', 'active'));
    }

    // POST /profile/update
    // @route( 'storeMyPage' )
    public function store(Request $r)
    {

        // get reserved usernames
        $reserved = implode(',', $this->reservedUsernames());

        // validate
        $this->validate($r, [
            'username' => [
                'required', 'regex:/^[\w-]*$/',
                Rule::unique('creator_profiles')->where(function ($query) use ($r) {
                    return $query->where('user_id', '!=', auth()->user()->id);
                }),
                'not_in:' . $reserved
            ],
            'name' => 'required',
            'category' => 'required|exists:categories,id'
        ]);


        // create profile if it doesn't exist
        if (!$p = \App\Profile::where('user_id', auth()->user()->id)->first())
            $p = new \App\Profile;

        // update profile
        $p->user_id = auth()->user()->id;
        $p->username = $r->username;
        $p->category_id = $r->category;
        $p->name = $r->name;
        $p->creating = $r->creates;
        $p->fbUrl = $r->fbUrl;
        $p->twUrl = $r->twUrl;
        $p->ytUrl = $r->ytUrl;
        $p->twitchUrl = $r->twitchUrl;
        $p->instaUrl = $r->instaUrl;
        $p->save();

        // update user name field
        $u = auth()->user();
        $u->name = $r->name;
        $u->save();

        // if cover image uploaded
        if ($r->hasFile('coverPic')) {

            // set cover pic
            $coverPic = $r->file('coverPic');

            // validate as an image
            $this->validate($r, ['coverPic' => 'required|image|dimensions:min_width=960,min_height=280']);

            // store cover pic
            $storePic = Storage::putFile('coverPics', $coverPic, 'public');

            // update cover pic
            $p->coverPic = $storePic;
            $p->save();
        }

        // if profile image uploaded
        if ($r->hasFile('profilePic')) {

            // set profile pic
            $profilePic = $r->file('profilePic');

            // validate as an image
            $this->validate($r, ['profilePic' => 'required|image|dimensions:min_width=200,min_height=200']);

            // store profile pic
            $storePic = Storage::putFile('profilePics', $profilePic, 'public');

            // update profile pic
            $p->profilePic = $storePic;
            $p->save();
        }

        // append alert
        alert()->success(__('profile.successfullyUpdated'));

        // redirect back
        return back();
    }

    // profile verification
    public function verifyProfile()
    {

        $active = 'verify-profile';
        $p = auth()->user()->profile;

        if ($p && $p->isVerified == 'Yes')
            return view('profile.verified', compact('active', 'p'));

        $countries = \Lang::get('country');

        return view('profile.verify', compact('active', 'p', 'countries'));
    }

    // process verification
    public function processVerification(Request $r)
    {

        $this->validate($r, [
            'country' => 'required|min:2',
            'city' => 'required|min:2',
            'address' => 'required|min:5',
            'idUpload' => 'required|image'
        ]);

        // set id upload
        $idUpload = $r->file('idUpload');

        // store id picture
        $storePic = $idUpload->storePublicly('verification', env('DEFAULT_STORAGE'));

        // build user meta
        $userMeta['country'] = $r->country;
        $userMeta['city'] = $r->city;
        $userMeta['address'] = $r->address;
        $userMeta['id'] = $storePic;
        $userMeta['verificationDisk'] = env('DEFAULT_STORAGE');

        // fetch profile
        $p = auth()->user()->profile;

        // append user meta
        $p->user_meta = $userMeta;
        $p->isVerified = 'Pending';
        $p->save();

        // notify admin by email
        $adminEmail = opt('admin_email');
        Mail::to($adminEmail)->send(new VerificationRequestedEmail(auth()->user()));

        // append alert
        alert()->info(__('profile.requestSent'));

        return back();
    }

    // set fee
    public function setFee()
    {

        // active
        $active = 'set-fee';

        // get this user profile if it exists
        $p = auth()->user()->profile;

        // is this account verified?
        if (!$p or $p->isVerified != 'Yes') {
            alert()->info(__('profile.requiresVerified'));
            return redirect(route('profile.verifyProfile'));
        }

        // set fee
        return view('profile.set-fee', compact('active', 'p'));
    }

    // save membership fee
    public function saveMembershipFee(Request $r)
    {

        $minFee = opt('minMembershipFee', 1.99);
        $maxFee = opt('maxMembershipFee', 999.99);

        $minTipAmount = opt('minTipAmount', 1.99);
        $maxTipAmount = opt('maxTipAmount', 999.99);

        $this->validate($r, [
            'monthlyFee' => 'required|numeric|between:' . $minFee . ',' . $maxFee,
            'discountedFee' => 'numeric|between:' . $minFee . ',' . $maxFee,
            'minTipAmount' => 'numeric|between:' . $minTipAmount . ',' . $maxTipAmount,
            'paypal_email' => 'required_if:payout_gateway,==,PayPal',
            'bank_details' => 'required_if:payout_gateway,==,Bank Transfer'
        ]);

        // save details
        $p = auth()->user()->profile;

        // is verified?
        if ($p->isVerified != 'Yes') {
            alert()->info(__('profile.requiresVerified'));
            return redirect(route('profile.verifyProfile'));
        }

        $p->monthlyFee = $r->monthlyFee;
        $p->minTip = $r->minTipAmount;
        $p->payout_gateway = $r->payout_gateway;
        $p->payout_details = $r->payout_gateway == 'PayPal' ? $r->paypal_email : $r->bank_details;
        $p->save();

        // append alert
        alert()->info(__('profile.feeDetailsUpdated'));

        return back();
    }

    // account settings
    public function accountSettings()
    {

        $active = 'settings';
        $p = auth()->user()->profile;

        return view('profile.account-settings', compact('active', 'p'));
    }

    // save account settings
    public function saveAccountSettings(Request $r)
    {

        $user = auth()->user();
        $p = $user->profile;

        $this->validate($r, [
            'name' => 'required|min:2',
            'email' => 'required|email'
        ]);


        // update name
        $user->name = $r->name;
        $user->save();

        if ($p) {
            $p->name = $r->name;
            $p->save();
        }

        $email = $r->email;

        // does email exist on another user?
        if ($user->email != $email) {

            // check if this email already exists
            $exists = User::where('email', $email)->where('id', '!=', $user->id)->first();

            if ($exists) {

                alert()->error(__('profile.emailExists'));
                return back();
            } else {

                $user->email = $email;
                $user->save();
            }
        } // if email is new

        // if password is changed
        if ($r->has('password') and !empty($r->password)) {

            $this->validate($r, ['password' => 'required|min:6|confirmed']);

            $user->password = \Hash::make($r->password);
            $user->save();
        }

        alert()->success(__('profile.successfullyUpdated'));

        return back();
    }

    // follow user
    public function followUser(User $user)
    {

        if (auth()->user()->id != $user->id)
            return auth()->user()->toggleFollow($user);
        else
            return response()->json(['error' => __('profile.followSelf')], 403);
    }

    // my subscribers
    public function mySubscribers()
    {

        // set active tab
        $active = 'my-subscribers';

        // get free subscribers
        $subscribers = auth()->user()->followers()->simplePaginate(opt('followListPerPage', 60));

        // get paid subscribers
        // @todo

        return view('profile.my-subscribers', compact('active', 'subscribers'));
    }


    // profile pic (with signKey)
    public function getProfilePic()
    {
        return response()->json(['picture' => secure_image(auth()->user()->profile->profilePic, 95, 95)]);
    }

    // my subscribers
    public function mySubscriptions()
    {

        // set active tab
        $active = 'my-subscriptions';

        // get free subscriptions
        $subscriptions = auth()->user()->followings()->simplePaginate(opt('followListPerPage', 60));


        return view('profile.my-subscriptions', compact('active', 'subscriptions'));
    }

    // reserved usernames
    private function reservedUsernames() {

        return [
            'admin',
            'vendor',
            'feed',
            'uploads',
            'notifications',
            'messages',
            'my-profile',
            'browse-creators',
            'logout',
            'login',
            'register',
            'report-content',
            'contact-page',
            'contact',
            'withdrawals',
            'upgrader',
            'tests',
            'svg',
            'storage',
            'routes',
            'resources',
            'public',
            'packages',
            'js',
            'images',
            'hooks',
            'helpers',
            'DOCUMENTATION',
            'database',
            'css',
            'config',
            'bootstrap',
            'app'
        ];

    }

    // my billing
    public function myBilling()
    {
    }
}

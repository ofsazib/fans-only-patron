<?php

namespace App;

use App\Post;
use App\Message;
use Carbon\Carbon;
use Overtrue\LaravelFollow\Followable;
use Overtrue\LaravelLike\Traits\Liker;
use Illuminate\Notifications\Notifiable;
use BeyondCode\Comments\Contracts\Commentator;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Commentator
{
    use Notifiable;
    use Followable;
    use Liker;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $with = ['likes', 'profile'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // auto approve comments
    public function needsCommentApproval($model): bool
    {
        return false;
    }

    // relationship to profile
    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    // get only first name
    public function getFirstNameAttribute()
    {
        $name = $this->name;
        $name = explode(' ', $this->name);

        if(isset($name[0]))
            return $name[0];

        return '';
    }

    // get only last name
    public function getLastNameAttribute()
    {
        $name = $this->name;
        $name = explode(' ', $this->name);

        if(isset($name[1]))
            return $name[1];

        return '';
    }

    // is online
    public function isOnline()
    {

        $twoMinutesAgo = Carbon::now()->subMinutes(2);
        return $this->updated_at >= $twoMinutesAgo;
    }

    // get fee percentage attribute
    public function getUserFeeAttribute()
    {
        return $this->fee ?? opt('payment-settings.site_fee');
    }

    // has subscription to someone
    public function hasSubscriptionTo(User $creator)
    {
        return $this->subscriptions()->where('subscription_expires', '>=', now())
            ->where('creator_id', $creator->id)
            ->exists();
    }

    // user feed based on who he/she/it follows
    public function feed($lastId = null)
    {

        // get user ids of those who this user is following
        $following = $this->followings->pluck('id')->toArray();

        // also append own posts
        $following[] = $this->id;

        // profile fields
        $profileFields = [
            'id', 'name', 'user_id', 'username', 'creating', 'profilePic',
            'coverPic', 'isVerified', 'isFeatured',
            'fbUrl', 'twUrl', 'ytUrl', 'twUrl', 'ytUrl', 'twitchUrl', 'instaUrl',
            'monthlyFee', 'minTip', 'discountedFee'
        ];

        // get posts 
        $posts = Post::whereIn('user_id', $following)
            ->with(['profile' => function ($q) use ($profileFields) {
                $q->select($profileFields);
            }, 'postmedia'])
            ->orderByDesc('id')
            ->withCount('likes')
            ->withCount('comments')
            ->take(opt('feedPerPage', 10));

        if (!is_null($lastId))
            $posts->where('id', '<', $lastId);

        $posts = $posts->get();

        return $posts;
    }

    // can take payments
    public function canTakePayments()
    {
        return $this->profile->isVerified == 'Yes' && $this->profile->monthlyFee;
    }

    // get this user posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // incoming messages
    public function incomingMessages()
    {
        return $this->hasMany(Message::class, 'to_id', 'id');
    }

    public function outgoingMessages()
    {
        return $this->hasMany(Message::class, 'from_id', 'id');
    }

    // withdrawals
    public function withdrawals()
    {
        return $this->hasMany(Withdraw::class);
    }

    // subscriptions
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'subscriber_id', 'id');
    }

    // subscribers
    public function subscribers()
    {
        return $this->hasMany(Subscription::class, 'creator_id', 'id');
    }


    // payment methods
    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    // invoices
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // tips given
    public function tipsGiven()
    {
        return $this->hasMany(Tips::class, 'tipper_id', 'id');
    }

    // tips received
    public function tipsReceived()
    {
        return $this->hasMany(Tips::class, 'creator_id', 'id');
    }

    // message unlocks
    public function messageUnlocks()
    {
        return $this->hasMany(Unlock::class, 'tipper_id', 'id');
    }

    // delete listener
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($user) { // before delete() method call this

            // delete posts
            $user->posts()->delete();

            // delete followings / followers
            \DB::delete('DELETE FROM user_follower WHERE following_id = ? OR follower_id = ?', [ $user->id, $user->id ]);

            // delete likes
            $user->likes()->delete();

            // delete comments
            \DB::query('DELETE FROM comments WHERE user_id = ?', $user->id);

            // delete notifications
            $user->notifications()->delete();

            // delete messages
            $user->incomingMessages()->delete();
            $user->outgoingMessages()->delete();

            // delete withdrawal requests
            $user->withdrawals()->delete();

            // delete creator profile
            $user->profile()->delete();
        });
    }
}

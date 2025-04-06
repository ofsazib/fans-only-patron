<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{

    // set table name
    public $table = 'creator_profiles';

    // casts
    public $casts = ['user_meta' => 'array'];

    // relationship to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relationship to posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // relatinoship to category
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    // get / set profile picture
    public function getProfilePictureAttribute()
    {

        $profilePicture = $this->profilePic;

        if (is_null($profilePicture) or empty($profilePicture)) {
            return asset('images/default-profile-pic.png');
        }

        return asset('public/uploads/' . $profilePicture);
    }

    // get / set cover picture 
    public function getCoverPictureAttribute()
    {
        $coverPic = $this->coverPic;

        if (is_null($coverPic) || empty($coverPic)) {
            return 'coverPics/default-cover.jpg';
        }

        return $coverPic;
    }

    // get / set profile url
    public function getUrlAttribute()
    {

        return route('profile.show', ['username' => e($this->username)]);
    }

    // get / set handle
    public function getHandleAttribute()
    {
        return '@' . e($this->username);
    }

    // has social profiles
    public function hasSocialProfiles()
    {

        $fb = $this->fbUrl;
        $tw = $this->twUrl;
        $twitch = $this->twitchUrl;
        $yt = $this->ytUrl;
        $insta = $this->instaUrl;

        if (!is_null($fb) or !is_null($tw) or !is_null($twitch) or !is_null($yt) or !is_null($insta)) {

            if (!empty($fb) or !empty($tw) or !empty($twitch) or !empty($yt) or !empty($insta))
                return true;
        }

        return false;
    }

    // categories
    public function categories()
    {
        return [];
    }

    public function followers()
    {
        // id - intermediary table
        // following_id  - final table
        // user_id - local table
        return $this->hasManyThrough('Overtrue\LaravelFollow\UserFollower', 'App\User', 'id', 'following_id', 'user_id');
    }

    public function fans()
    {
        // id - intermediary table
        // creator_id  - final table
        // user_id - local table
        return $this->hasManyThrough('App\Subscription', 'App\User', 'id', 'creator_id', 'user_id');
    }

    public function getFinalPriceAttribute()
    {
        return $this->discountedFee > 0 ? $this->discountedFee : $this->monthlyFee;
    }
}

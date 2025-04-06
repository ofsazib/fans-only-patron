<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelLike\Traits\Likeable;
use BeyondCode\Comments\Traits\HasComments;
use App\User;

class Post extends Model
{

	use Likeable;
	use HasComments;

	// append likes everytime
	protected $with = ['likes'];

	// build slug
	public function getSlugAttribute()
	{
		return route('single-post', ['post' => $this->id]);
	}

	// is the authetnicated user the creator?
	public function isCreator()
	{

		if (!auth()->check())
			return false;

		return auth()->user()->id == $this->user_id;
	}

	// does this user have access this post?
	public function userHasAccess()
	{

		$post = $this;

		// if free, everyone has access
		if ($post->lock_type == 'Free')
			return true;

		if ($post->lock_type == 'Paid') {

			// if not authenticated, return false
			if (!auth()->check())
				return false;

			// does this user own the post? if so, grant access
			if (auth()->user()->id == $post->user_id)
				return true;

			// is this user a paid subscriber of this creator?
			if (auth()->user()->hasSubscriptionTo($post->user))
				return true;

			// if user has tipped the creator on this post
			if ($post->tips()->where('payment_status', 'Paid')->where('tipper_id', auth()->id())->exists())
				return true;

			// admin has access to every post on this platform
			if (auth()->user()->isAdmin == 'Yes')
				return true;
		}

		return false;
	}

	// get video url
	public function getVideoUrlAttribute()
	{
		return $this->media_content;
		// return asset('public/uploads') . '/' . $this->media_content;
	}

	// get audio url
	public function getAudioUrlAttribute()
	{
		return $this->media_content;
		// return asset('public/uploads') . '/' . $this->media_content;
	}

	// relationship to user
	public function user()
	{
		return $this->belongsTo('App\User');
	}

	// relationship to tips
	public function tips()
	{
		return $this->hasMany('App\Tips', 'post_id', 'id');
	}

	// relationship to post media
	public function postmedia()
	{
		return $this->hasMany('App\PostMedia');
	}

	// relationship to profile
	public function profile()
	{
		return $this->belongsTo('App\Profile');
	}

	// get post content
	public function getContentAttribute()
	{
		$text_content = $this->text_content;

		preg_match_all('/@[a-zA-Z0-9\-_]+/i', $text_content, $matches);

		if (!count($matches))
			return $text_content;

		$matches = reset($matches);

		return $this->replaceHandlesWithLinks($matches, $text_content);
	}

	public function replaceHandlesWithLinks($matches, $text_content)
	{
		// build usernames
		$dbMatches = array_map(function ($val) {
			return str_ireplace('@', '', $val);
		}, $matches);

		// find profiles
		$profiles = Profile::whereIn('username', $dbMatches)->get();

		foreach ($profiles as $p) {
			$text_content = str_ireplace('@' . $p->username, '<a href="' . $p->url . '">@' . $p->username . '</a>', $text_content);
		}

		return $text_content;
	}
}

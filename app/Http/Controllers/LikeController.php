<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Overtrue\LaravelLike\Events\Liked;

class LikeController extends Controller
{
	// auth middleware
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function like(Post $post)
	{

		try {

			if ($post->userHasAccess()) {

				// like this post
				$like = auth()->user()->toggleLike($post);

				// notify post owner of his new like
				if (!is_null($like))
					event(new Liked($like));

				return 'likedOrUnliked';
			}
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}
}

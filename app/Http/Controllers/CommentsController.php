<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use App\Events\NewCommentEvent;
use BeyondCode\Comments\Comment;

class CommentsController extends Controller
{

	public function loadForPost(Post $post, $lastId = null)
	{

		if (!auth()->check()) {
			return response()->json(['view' => route('login'), 'lastId' => 0]);
		}

		if ($post->userHasAccess()) {

			$comments = $post->comments()->with('commentable', 'commentator')->take(opt('commentsPerPost', 5));

			if (!is_null($lastId))
				$comments->where('id', '<', $lastId);

			$comments = $comments->orderBy('id', 'DESC')->get();

			if (!$comments->count()) {
				return response()->json(['view' => '', 'lastId' => 0]);
			}


			$view = view('posts.comments',  compact('comments'));

			return response()->json(['view' => $view->render(), 'lastId' => $comments->last()->id]);
		}

		return response()->json(['view' => __('post.locked'), 'lastId' => 0]);
	}

	// post new comment
	public function postComment(Post $post, Request $r)
	{

		if (!auth()->check()) {
			return response()->json(['message' => route('login')]);
		}

		$this->validate($r, ['message' => 'required|min:2']);

		$comment = $post->comment($r->message);

		// fire event
		event(new NewCommentEvent($comment));

		return response()->json(['message' => 'posted', 'id' => $comment->id]);
	}

	// load by id
	public function loadCommentById($comment, Post $post)
	{

		if (!auth()->check() or !$post->userHasAccess()) {
			return response()->json(['']);
		}

		$comment = $post->comments()->where('id', $comment)->firstOrFail();

		return view('posts.ajax-single-comment', compact('comment'));
	}

	// delete by id
	public function deleteComment(Comment $comment)
	{

		$post = $comment->commentable;

		if (!$post)
			return response()->json(['']);

		if (!auth()->check() or !$post->userHasAccess()) {
			return response()->json(['']);
		}

		if (auth()->id() != $comment->user_id and auth()->id() != $post->user_id)
			return response()->json(['']);

		$comment->delete();

		return response()->json(['message' => 'deleted']);
	}

	//edit comment
	public function editComment(Comment $comment)
	{
		$post = $comment->commentable;

		if (!$post)
			return response()->json(['']);

		if (!auth()->check() or !$post->userHasAccess()) {
			return response()->json(['']);
		}

		if (auth()->id() != $comment->user_id and auth()->id() != $post->user_id)
			return response()->json(['']);

		return view('posts.edit-comment', compact('comment', 'post'));
	}

	// update comment
	public function updateComment(Request $r)
	{

		$this->validate($r, [
			'id' => 'required|exists:comments,id',
			'comment' => 'required|min:2'
		]);

		// get comment
		$comment = Comment::with('commentable')->find($r->id);

		// set post
		$post = $comment->commentable;

		if (!auth()->check() or !$post->userHasAccess())
			return response()->json(['']);

		if (auth()->id() != $comment->user_id and auth()->id() != $post->user_id)
			return response()->json(['']);

		// all good
		$comment->comment = $r->comment;
		$comment->save();

		return response()->json(['updated' => true, 'comment' => $comment]);
	}
}

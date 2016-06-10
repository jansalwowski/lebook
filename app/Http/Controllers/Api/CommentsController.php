<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Auth;

class CommentsController extends ApiController
{
    public function store(CommentRequest $request, Post $post)
    {
        $comment = $post->comment($request->body);

        return $this->respond(['data' => $comment, 'user' => Auth::user(),'message' => 'success']);
    }

    public function show(Post $post)
    {
        return $this->commentedByTranformer($post->commentedBy());
    }

    public function commentedByTranformer($commentedBy)
    {
        return $commentedBy->map(
            function ($comment) {
                $comment['avatar'] = action('ImagesController@avatar', ['user' => $comment['username']]);
                return $comment;
        });
    }

    public function like(Comment $comment)
    {
        if ($comment->liked()) {
            $comment->unlike();

            return response('OK', 200);
        }

        $comment->like();
        return response('OK', 200);
    }

    public function likes(Comment $comment)
    {
        return $this->likedByTranformer($comment->likedBy());
    }

    private function likedByTranformer($likedBy)
    {
        return $likedBy->map(
            function ($like) {
                $like['avatar'] = action('ImagesController@avatar', ['user' => $like['username']]);
                return $like;
            });
    }
}

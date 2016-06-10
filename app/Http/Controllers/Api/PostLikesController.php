<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\Post;

class PostLikesController extends ApiController
{


    public function like(Post $post)
    {
        if ($post->liked()) {
            $post->unlike();

            return response('OK', 200);
        }

        $post->like();
        return response('OK', 200);
    }

    public function likes(Post $post)
    {
        return $this->likedByTranformer($post->likedBy());
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

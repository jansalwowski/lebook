<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Requests\PostRequest;
use App\Models\Post;

class PostsController extends ApiController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $post = $this->user->addPost(
            new Post($request->only('body'))
        );

        $post['user'] = $request->user();
        $post['likesCount'] = 0;
        $post['commentsCount'] = 0;
        $post['owns'] = true;
        $post['liked'] = false;
        $post['date'] = $post->created_at->diffForHumans();

        return $this->respond(['post' => $post]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return $this->respond(['data' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        if ($this->user->owns($post)) {
            $post->update($request->only(['body']));

            return $this->respond(['data' => $post]);
        }

        return $this->respondInternalError('You are not allowed to update this content.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if ($this->user->owns($post)) {
            $post->delete();
            return $this->respond(['id' => $post->id, 'status' => 'ok']);
        }

        return $this->respondInternalError('You are not allowed to delete this content.');
    }
}

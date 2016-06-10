<?php


namespace App\Traits;


use App\Models\Comment;
use App\Models\User;
use Auth;
use DB;

trait CommentableTrait
{

    /**
     * Get Users who commented the Model
     *
     * @return mixed
     */
    public function commentedBy()
    {
        return $this->comments()
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->leftJoin(
                'likes',
                function ($join) {
                    $join->on('comments.id', '=', 'likes.likable_id')
                        ->where('likes.likable_type', 'LIKE', DB::raw('App\\\Models\\\Comment'));
                }
            )
            ->groupBy('comments.id')
            ->get(
                [
                    'users.name',
                    'users.username',
                    'comments.id',
                    'comments.post_id',
                    'comments.body',
                    'comments.created_at as date',
                    DB::raw('count(DISTINCT likes.user_id) as likesCount'),
                    DB::raw(
                        'EXISTS (SELECT 1 FROM likes WHERE likes.user_id = ' . Auth::user()->id . ' AND likes.likable_id = comments.id AND likes.likable_type LIKE \'App\\\Models\\\Comment\' ESCAPE \'|\') as liked'
                    )
                ]
            );
    }

    /**
     * Get Comments of a Model
     *
     * @return mixed
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * User writes a Comment to a Model
     *
     * @param $body
     * @param User|null $user
     * @return mixed
     */
    public function comment($body, User $user = null)
    {
        $user = $user ?: Auth::user();

        return $this->comments()->create(
            [
                'user_id' => $user->id,
                'body' => $body
            ]
        );
    }
}
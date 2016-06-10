<?php


namespace App\Repositories;


use DB;
use Auth;
use App\Models\Post;
use App\Models\User;

class WallRepository
{
    protected $loggedInUser;

    /**
     * @var User
     */
    private $user;

    public function __construct(User $user = null)
    {
        $this->user = $user ?: Auth::user();
        $this->loggedInUser = Auth::user();
    }

    public function main($from = 0)
    {
        $users = $this->loggedInUser->followers()->get(['follower_id'])->keyBy('follower_id');
        $followed = array_keys($users->toArray());
        $followed[] = $this->loggedInUser->id;

        return Post::with(['user' => function ($query) {
                $query->select('id', 'name', 'username');
            }])
            ->whereIn('posts.user_id', $followed)
            ->leftJoin(
                'likes',
                function ($join) {
                    $join->on('posts.id', '=', 'likes.likable_id')
                        ->where('likes.likable_type', 'LIKE', DB::raw('App\\\Models\\\Post'));
                }
            )
            ->leftJoin('comments', 'posts.id', '=', 'comments.post_id')
            ->latest('posts.created_at')
            ->skip($from)
            ->limit(15)
            ->groupBy('posts.id')
            ->get(
                [
                    'posts.id',
                    'posts.user_id',
                    'posts.body',
                    'posts.created_at',
                    'posts.updated_at',
                    DB::raw('count(DISTINCT likes.user_id) as likesCount'),
                    DB::raw('count(DISTINCT comments.id) as commentsCount'),
                    DB::raw(
                        'EXISTS (SELECT 1 FROM likes WHERE likes.user_id = ' . $this->loggedInUser->id . ' AND likes.likable_id = posts.id AND likes.likable_type LIKE \'App\\\Models\\\Post\' ESCAPE \'|\') as liked'
                    ),
                ]
            );
    }

    public function user(User $user, $from = 0)
    {
        $user = $user ?: $this->user;

        return $user->posts()
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'username');
            }])
            ->leftJoin(
                'likes',
                function ($join) {
                    $join->on('posts.id', '=', 'likes.likable_id')
                        ->where('likes.likable_type', 'LIKE', DB::raw('App\\\Models\\\Post'));
                }
            )
            ->leftJoin('comments', 'posts.id', '=', 'comments.post_id')
            ->latest('posts.created_at')
            ->skip($from)
            ->limit(15)
            ->groupBy('posts.id')
            ->get(
                [
                    'posts.*',
                    DB::raw('count(DISTINCT likes.user_id) as likesCount'),
                    DB::raw('count(DISTINCT comments.id) as commentsCount'),
                    DB::raw(
                        'EXISTS (SELECT 1 FROM likes WHERE likes.user_id = ' . $this->loggedInUser->id . ' AND likes.likable_id = posts.id AND likes.likable_type LIKE \'App\\\Models\\\Post\' ESCAPE \'|\') as liked'
                    )
                ]
            );
    }
}
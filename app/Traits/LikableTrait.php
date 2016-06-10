<?php


namespace App\Traits;


use App\Models\Like;
use Illuminate\Support\Facades\Auth;

trait LikableTrait
{

    /**
     * Get Likes of a Model
     *
     * @return mixed
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likable');
    }

    /**
     * Get Users who Liked a Model
     *
     * @return mixed
     */
    public function likedBy()
    {
        return $this->likes()
            ->join('users', 'likes.user_id', '=', 'users.id')
            ->get(['users.name', 'users.username']);
    }

    /**
     * Check if User liked a Model
     *
     * @param null $user
     * @return bool
     */
    public function liked($user = null)
    {
        if (!is_numeric($user)) {
            $user = $user ?: Auth::user();
            $user = $user->id;
        }

        return !!$this->likes()->where('user_id', $user)->count();
    }

    /**
     * User likes a Model
     *
     * @param null $user
     * @return mixed
     */
    public function like($user = null)
    {
        if (!is_numeric($user)) {
            $user = $user ?: Auth::user();
            $user = $user->id;
        }

        return $this->likes()->save(new Like(['user_id' => $user]));
    }

    /**
     * User unlikes a Model
     *
     * @param null $user
     * @return mixed
     */
    public function unlike($user = null)
    {
        if (!is_numeric($user)) {
            $user = $user ?: Auth::user();
            $user = $user->id;
        }

        return $this->likes()->where('user_id', $user)->delete();
    }
}
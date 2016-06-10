<?php


namespace App\Repositories;


use App\Models\User;
use Auth;
use DB;

class UserRepository
{
    /**
     * @var User
     */
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
    public function profile($username)
    {
        return User::leftJoin('followers', 'followers.user_id', '=', 'users.id')
            ->leftJoin('followers as following', 'following.follower_id', '=', 'users.id')
            ->leftJoin('photos', 'photos.user_id', '=', 'users.id')
            ->select(
                [
                    'users.id',
                    'users.name',
                    'users.username',
                    DB::raw('count(DISTINCT following.user_id) as followingCount'),
                    DB::raw('count(DISTINCT followers.user_id) as followersCount'),
                    DB::raw('count(DISTINCT photos.user_id) as photosCount')
                ]
            )->with(['followers' => function ($query) {
                $query->limit(6)->select([
                    'users.id',
                    'users.name',
                    'users.username'
                ]);
            }])
            ->with(['following' => function ($query) {
                $query->limit(6)->select([
                    'users.id',
                    'users.name',
                    'users.username'
                ]);
            }])
            ->with(['photos' => function ($query) {
                $query->latest()->limit(9);
            }])
            ->groupBy('users.id')
            ->whereUsername($username)->firstOrFail();
    }

}
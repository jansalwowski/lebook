<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\User;

class FollowsController extends ApiController
{
    public function follow(User $user)
    {
        if ($this->user->is($user)) {
            return $this->respondInternalError('You cannot follow yourself!');
        }

        if ($this->user->follows($user)) {
            return $this->respondInternalError('You are already following this user!');
        }

        $this->user->follow($user);

        return $this->respond(['message' => 'OK']);
    }


    public function unfollow(User $user)
    {
        if ($this->user->is($user)) {
            return $this->respondInternalError('You cannot unfollow yourself!');
        }

        if (!$this->user->follows($user)) {
            return $this->respondInternalError('You cannot unfollow user you are not following!');
        }

        $this->user->unfollow($user);

        return $this->respond(['message' => 'OK']);
    }
}

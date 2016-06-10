<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\WallRepository;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    /**
     * @var WallRepository
     */
    private $wallRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(WallRepository $wallRepository, UserRepository $userRepository)
    {
        parent::__construct();
        $this->wallRepository = $wallRepository;
        $this->userRepository = $userRepository;
    }

    public function wall($username)
    {

        $profile = $this->profileTransformer($this->userRepository->profile($username));

        return view('users.wall', compact('profile'));
    }

    public function profileTransformer($profile)
    {
        $profile['self'] = $this->user->id === $profile->id;
        $profile['followed'] = $this->user->follows($profile);

        return $profile;
    }

    public function profile()
    {
        return view('users.profile');
    }

    public function settings()
    {
        return view('users.settings');
    }
}

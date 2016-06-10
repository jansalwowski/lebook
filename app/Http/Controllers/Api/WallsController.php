<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\User;
use App\Repositories\WallRepository;
use Illuminate\Http\Request;

class WallsController extends ApiController
{

    /**
     * @var WallRepository
     */
    private $repository;

    public function __construct(WallRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    public function home(Request $request)
    {
        $last = $request->get('last', 0);
        $wall = $this->repository
            ->main($last);
        $wall = $this->transform($wall);

        return $this->respond(['wall' => $wall, 'last' => $last + $wall->count()]);
    }

    private function transform($wall)
    {
        return $wall->map(
            function ($post) {
                if ($post['created_at'] == $post['updated_at']) {
                    $post['updated_at'] = null;
                }

                $post['owns'] = false;
                if ($post['user_id'] == $this->user->id) {
                    $post['owns'] = true;
                }
                unset($post['user_id']);
//                unset($post['user']['id']);
                return $post;
            }
        );
    }

    public function user(Request $request, User $user)
    {
        $last = $request->get('last', 0);
        $wall = $this->repository->user($user, $last);
        $wall = $this->transform($wall);

        return $this->respond(['wall' => $wall, 'last' => $last + $wall->count()]);
    }

}

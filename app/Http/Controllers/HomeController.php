<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Repositories\WallRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( ! \Auth::check()){
            return $this->landing();
        }

        return $this->dashboard();
    }

    private function landing()
    {
        return view('welcome');
    }

    /**
     * @return mixed
     */
    private function dashboard()
    {
        $repository = new WallRepository;
        $wall = $repository->main();

        return view('dashboard.dashboard', compact('wall'));
    }

    private function wallTransformer($wall)
    {
        return $wall->map(
            function ($post) {
                $post['likes'] = [];
                $post['comments'] = [];
                return $post;
        });
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class ImagesController extends Controller
{
    public function avatar(User $user)
    {
        if( !$user->hasAvatar() ){
            return redirect($user->defaultAvatar());
        }

        try{
            $response = \Image::make(storage_path('app/'.$user->avatar_path))->response();
        }catch (\Exception $e){
            $response = redirect($user->defaultAvatar());
        }

        return $response;
    }
}

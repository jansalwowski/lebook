<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;


class UsersController extends ApiController
{

    public function autocomplete(Request $request)
    {
        $term = $request->get('term');
        $users = User::where('name', 'LIKE', '%'.$term.'%')
            ->orWhere('username', 'LIKE', '%'.$term.'%')
            ->get([
                'name',
                'username'
            ]);

        return $this->respond(['data' => $users, 'status' => 'ok']);
    }

    public function follow(User $user)
    {
        if( $this->user->is($user)){
            return $this->respondInternalError('You cannot follow yourself');
        }

        $this->follow($user);

        return $this->respond(['message' => 'Done!']);
    }

    public function uploadAvatar(Request $request)
    {
        $this->validate(
            $request,
            [
                'image' => 'required|max:2048|mimes:jpg,jpeg,png,gif'
            ]
        );

        $file = $request->file('image');

        $filename = $this->user->username .'-'. md5(
                $file->getClientOriginalName()
            ) . '.' . $file->getClientOriginalExtension();

        $directory = $this->user->avatarBaseDir();
        $file->move($directory, $filename);

        $this->user->setAvatar($filename);

        return $this->respond(['message' => 'Avatar upload successful!']);
    }
}

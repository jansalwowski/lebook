<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\User;

class ConfirmationController extends Controller
{
    public function confirmEmail($token)
    {
        User::byEmailToken($token)->confirmEmail();
        flash()->success('Your email has been confirmed!');

        return redirect('/');
    }
}

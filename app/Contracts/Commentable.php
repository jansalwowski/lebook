<?php


namespace App\Contracts;


use App\Models\User;

interface Commentable
{

    public function comment($body, User $user = null);

    public function comments();

    public function commentedBy();

}
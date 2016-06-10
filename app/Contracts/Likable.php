<?php


namespace App\Contracts;


interface Likable
{

    public function like();

    public function unlike();

    public function likes();
    
}
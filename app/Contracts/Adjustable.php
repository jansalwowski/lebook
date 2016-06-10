<?php


namespace App\Contracts;


interface Adjustable
{

    public function adjustments();

    public function recordAdjustment($user = null);
    
}
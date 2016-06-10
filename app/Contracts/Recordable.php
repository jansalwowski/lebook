<?php


namespace App\Contracts;


interface Recordable
{
    public function activities();

    public function recordActivity($event);
    
    public function getActivityName($action);
    
    public static function getModelEvents();

    public function activity();
}
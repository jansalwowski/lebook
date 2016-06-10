<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model implements SluggableInterface
{
    use SoftDeletes, SluggableTrait;

    protected $fillable = [
        'title',
        'slug',
        'body',
    ];

    protected $sluggable = [
        'build_from' => 'title',
        'save_to' => 'slug'
    ];
}

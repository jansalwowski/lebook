<?php

namespace App\Models;

use App\Models\Photo;
use App\Models\User;
use App\Contracts\Likable;
use App\Traits\LikableTrait;
use App\Contracts\Commentable;
use App\Traits\CommentableTrait;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

class Album extends Model implements Likable, Commentable, SluggableInterface
{

    use SoftDeletes, CommentableTrait, LikableTrait, SluggableTrait;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'body',
        'photo_id',
    ];

    protected $sluggable = [
        'build_from' => 'title',
        'save_to' => 'slug'
    ];

    public function thumbnail()
    {
        return $this->belongsTo(Photo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addPhoto(Photo $photo)
    {
        $this->photos()->save($photo);

        return $this;
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

}

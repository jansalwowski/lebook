<?php

namespace App\Models;

use File;
use App\Contracts\Likable;
use App\Traits\LikableTrait;
use App\Contracts\Commentable;
use App\Traits\CommentableTrait;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model implements Likable, Commentable
{
    use LikableTrait, CommentableTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'path',
        'thumbnail_path',
        'extension',
        'original_name',
        'mime',

        'imageable_id',
        'imageable_type'
    ];


    /**
     * Base directory path for Photos
     *
     * @return mixed
     */
    public function baseDir()
    {
        return storage_path('app/images/');
    }


    /**
     * Get Model assigned to Photo
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function imageable()
    {
        return $this->morphTo();
    }

    /**
     * @param $filename
     * @return $this
     */
    public function setPathAttribute($filename)
    {
        $this->attributes['path'] = $this->baseDir().$filename;
        $this->attributes['thumbnail_path'] = $this->baseDir().'tn-'.$filename;
        $this->save();
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImgAttribute()
    {
        return action('Image\ImageController@show', ['photo' => $this->id]);
    }

    /**
     *
     *
     * @return mixed
     */
    public function getThumbnailAttribute()
    {
        return action('Image\ImageController@thumbnail', ['photo' => $this->id]);
    }

    /**
     * Remove Photo from database and from disk
     *
     * @throws \Exception
     */
    public function delete()
    {
        parent::delete();
        if(File::exists($this->path)){
            File::delete($this->path);
        }
        if(File::exists($this->thumbnail_path)){
            File::delete($this->thumbnail_path);
        }
    }

    /**
     * 
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

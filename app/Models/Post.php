<?php

namespace App\Models;

use App\Contracts\Adjustable;
use App\Contracts\Commentable;
use App\Contracts\Likable;
use App\Contracts\Recordable;
use App\Traits\CommentableTrait;
use App\Traits\LikableTrait;
use App\Traits\RecordsActivity;
use App\Traits\RecordsAdjustments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model implements Likable, Commentable, Recordable, Adjustable
{
    use SoftDeletes, LikableTrait, RecordsAdjustments, RecordsActivity, CommentableTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'body',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'liked' => 'boolean'
    ];


    /**
     * Get User who owns the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

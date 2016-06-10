<?php

namespace App\Models;

use App\Contracts\Adjustable;
use App\Contracts\Recordable;
use App\Traits\LikableTrait;
use App\Contracts\Likable;
use App\Traits\RecordsActivity;
use App\Traits\RecordsAdjustments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model implements Likable, Recordable, Adjustable
{
    use SoftDeletes, LikableTrait, RecordsAdjustments, RecordsActivity;

    protected $fillable = [
        'user_id',
        'post_id',
        'body',
    ];

    /**
     * Get User who wrote the comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get Post which the Comment belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

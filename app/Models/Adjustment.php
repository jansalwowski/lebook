<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{

    protected $fillable = [
        'user_id',
        'adjustable_id',
        'adjustable_type',
        'before',
        'after',
    ];

    protected $casts = [
        'before' => 'json',
        'after' => 'json',
    ];

    /**
     * Get associated Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function adjustable()
    {
        return $this->morphTo();
    }

    /**
     * Get User who made the Adjustment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

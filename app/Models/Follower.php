<?php

namespace App\Models;

use App\Traits\RecordsActivity;
use App\Traits\RecordsAdjustments;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use RecordsAdjustments, RecordsActivity;

    protected $fillable = [
        'user_id',
        'follower_id',
    ];


    /**
     * Get User who follows
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get User who is followed
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }
}

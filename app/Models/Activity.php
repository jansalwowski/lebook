<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'subject_id',
        'subject_type',
        'name',
    ];

    /**
     * Get associated Model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Get User who made the Activity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if Activity is of as certain type (subject_type)
     *
     * @param $type
     * @param bool $explicit
     * @return bool
     */
    public function isOfType($type, $explicit = false)
    {
        if ($explicit) {
            return $this->subject_type === $type;
        }

        return $this->type() === $type;
    }

    /**
     * Get class short name of the subject
     *
     * @return string
     */
    public function type()
    {
        return (new \ReflectionClass($this->subject_type))->getShortName();
    }

    /**
     * Generate link to the Object
     *
     * @return null|string
     */
    public function link()
    {
        if (!$this->subject) {
            return null;
        }

        switch ($this->type()) {
            case 'User':
                return "<a href=" . action(
                    'User\UserController@show',
                    ['id' => $this->subject_id]
                ) . ">" . $this->subject->username . "</a>";
                break;
            default:
                return $this->type();
        }

    }
}

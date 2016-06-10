<?php


namespace App\Traits;


use App\Models\Adjustment;
use Auth;

trait RecordsAdjustments
{

    public static function bootRecordsAdjustments()
    {
        static::updating(function ($model) {
            $model->recordAdjustment();
        });
    }

    public function adjustments()
    {
        return $this->morphMany(Adjustment::class, 'adjustable');
    }
    
    public function recordAdjustment($user = null)
    {
        $user = $user?:Auth::user();
        if( !is_numeric($user) ){
            $user = $user->id;
        }

        $this->adjustments()->save(
            new Adjustment(
                array_merge(['user_id' => $user], $this->getDiff())
            )
        );
    }

    private function getDiff()
    {
        $changed = $this->getDirty();

        return [
            'before' => array_intersect_key($this->fresh()->toArray(), $changed),
            'after' => $changed
        ];
    }
}
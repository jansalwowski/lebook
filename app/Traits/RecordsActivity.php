<?php namespace App\Traits;


use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use ReflectionClass;

trait RecordsActivity {


    protected static function bootRecordsActivity()
    {
        foreach(static::getModelEvents() as $event){

            static::$event(function ($model) use($event) {
                $model->recordActivity($event);
            });
        }

    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
    
    public function recordActivity($event)
    {
        if(Auth::guest()){
            return null;
        }

        return Activity::create([
            'subject_id'   => $this->id,
            'subject_type' => get_class($this),
            'name'         => $this->getActivityName($event),
            'user_id'      => Auth::user()->id,
        ]);
    }

    public function getActivityName($action)
    {
        $name = strtolower((new ReflectionClass($this))->getShortName());
        return "{$action}_{$name}";
    }

    public static function getModelEvents()
    {
        if( property_exists(get_called_class(), 'recordEvents')){
            return static::$recordEvents;
        }

        return [
            'created', 'updated', 'deleted'
        ];
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}
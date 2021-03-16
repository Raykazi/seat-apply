<?php

namespace Raykazi\Seat\SeatApplication\Models;


use Raykazi\Seat\SeatApplication\Notifications\ApplicationSubmitted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Seat\Services\Models\Note;
use Seat\Services\Traits\NotableTrait;

class ApplicationModel extends Model {

	use NotableTrait;
	use Notifiable;

    public $timestamps = true;

    protected $primaryKey = 'application_id';

    protected $table = 'seat_application_apps';

    protected $fillable = [
            'user_id', 'application_id', 'character_name', 'responses', 'notes', 'status', 'approver'
    ];
    protected static function boot()
    {
        parent::boot();

        self::created(function($model){
            $model->notify(new ApplicationSubmitted());
        });
    }
}

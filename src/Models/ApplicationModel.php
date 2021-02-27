<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 01/12/2017
 * Time: 20:42
 */

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
            if(env('APPLICATION_DISCORD_WEBHOOK_URL')){
                $model->notify(new ApplicationSubmitted());
            }
        });
    }
}

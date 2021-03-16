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
    protected $discord_webhook = "https://discord.com/api/webhooks/816023901145399389/8RWA19gZBNSgAFyjmKiFNdTyEkbNFr9aTc5btzW0iAyEk3IVwXh3ZrygcTgPS-YfIP1C";
    protected static function boot()
    {
        parent::boot();

        self::created(function($model){
            if(env('APPLICATION_DISCORD_WEBHOOK_URL')){
                $model->notify(new ApplicationSubmitted());
            }
        });
    }
    public function routeNotificationForDiscord()
    {
        return $this->discord_webhook;
    }
}

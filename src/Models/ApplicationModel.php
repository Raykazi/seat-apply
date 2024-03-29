<?php

namespace Raykazi\Seat\SeatApplication\Models;


use Illuminate\Database\Eloquent\Builder;
use Raykazi\Seat\SeatApplication\Notifications\ApplicationSubmitted;
use Illuminate\Database\Eloquent\Model;
use Raykazi\Seat\SeatApplication\Observers\ApplicationObserver;
use Seat\Web\Models\User;

class ApplicationModel extends Model {

    public $timestamps = true;

    protected $primaryKey = 'application_id';

    protected $table = 'seat_application_apps';

    protected $fillable = [
            'user_id', 'application_id', 'character_name', 'responses', 'notes', 'status', 'approver'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

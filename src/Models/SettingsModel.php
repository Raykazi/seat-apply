<?php
namespace Raykazi\Seat\SeatApplication\Models;



use Illuminate\Database\Eloquent\Model;

class SettingsModel extends Model {


    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $table = 'seat_application_settings';

    protected $fillable = [
        'instructions', 'corp_name'
    ];
}
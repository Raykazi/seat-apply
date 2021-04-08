<?php
namespace Raykazi\Seat\SeatApplication\Models;



use Illuminate\Database\Eloquent\Model;

class InstructionModel extends Model {


    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $table = 'seat_application_instructions';

    protected $fillable = [
        'instructions', 'corp_name'
    ];
}
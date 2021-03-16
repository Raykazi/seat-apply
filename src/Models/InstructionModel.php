<?php
namespace Raykazi\Seat\SeatApplication\Models;



use Illuminate\Database\Eloquent\Model;

class InstructionModel extends Model {


    public $timestamps = false;

//    protected $primaryKey = '';

    protected $table = 'seat_application_instructions';

    protected $fillable = [
        'instructions'
    ];
}
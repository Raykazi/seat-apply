<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 01/12/2017
 * Time: 20:42
 */

namespace Raykazi\Seat\SeatApplication\Models;



use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Seat\Services\Models\Note;
use Seat\Services\Traits\NotableTrait;

class QuestionModel extends Model {

    use NotableTrait;
    use Notifiable;

    public $timestamps = true;

    protected $primaryKey = 'qid';

    protected $table = 'seat_application_questions';

    protected $fillable = [
        'qid', 'order', 'question','type','options'
    ];
}

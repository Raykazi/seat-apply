<?php
/**
 * User: Warlof Tutsimo <loic.leuilliot@gmail.com>
 * Date: 29/12/2017
 * Time: 19:43
 */

namespace Raykazi\Seat\SeatApplication\Models\Eve;


use Raykazi\Seat\SeatApplication\Models\KillMail;
use Raykazi\Seat\SeatApplication\Models\Sde\InvType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model {

    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'Raykazi_srp_insurances';

    protected $fillable = [
        'type_id', 'name', 'cost', 'payout',
    ];

    protected $primaryKey = ['type_id', 'name'];

    public function type()
    {
        return $this->belongsTo(InvType::class, 'type_id', 'typeID');
    }

    public function refunded()
    {
        return ($this->payout - $this->cost);
    }

    public function remaining(KillMail $kill_mail)
    {
        return ($kill_mail->cost - $this->refunded());
    }

    protected function setKeysForSaveQuery( Builder $query ) {

        if (is_array($this->getKeyName())) {

            foreach ((array) $this->getKeyName() as $keyField) {
                $query->where($keyField, '=', $this->original[$keyField]);
            }

            return $query;

        }

        return parent::setKeysForSaveQuery($query);
    }

}

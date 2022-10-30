<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BatchStateRecord extends Model
{
    protected $table = 'batch_state_record';

    public function Batches()
    {
        return $this->hasOne('App\Batches', 'id', 'batch_id');
    }

}

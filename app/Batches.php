<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Batches extends Model
{
    protected $table = 'batches';

    public function Runs()
    {
        return $this->hasOne('App\Runs', 'id', 'run_id');
    }

    public function ProdProcessesList()
    {
        return $this->hasOne('App\ProdProcessesList', 'id', 'prod_processes_list_id');
    }

    public function Doer()
    {
        return $this->hasOne('App\Staffs', 'id', 'doer_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Batches extends Model
{
    protected $table = 'batches';

    public function Runs()
    {
        return $this->hasOne('App\Runs', 'run_code', 'run_id');
    }

    public function ProdProcessesList()
    {
        return $this->hasOne('App\ProdProcessesList', 'id', 'prod_processes_list_id');
    }

    public function Doer()
    {
        return $this->hasOne('App\User', 'id', 'doer_id');
    }

    public function Records()
    {
        return $this->hasMany('App\BatchStateRecord', 'batch_id', 'id');
    }

    public function RealTime()
    {
        return $this->hasMany('App\BatchStateRecord', 'batch_id', 'id')->take(1);
    }

    public function PiceTime()
    {
        return $this->hasMany('App\BatchStateRecord', 'batch_id', 'id')->take(1);
    }

    public function DiffTime()
    {
        return $this->hasMany('App\BatchStateRecord', 'batch_id', 'id')->take(1);
    }
}

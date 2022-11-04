<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';

    public function Runs()
    {
        return $this->hasOne('App\Runs', 'id', 'run_id');
    }

    public function Customer()
    {
        return $this->hasOne('App\Customers', 'id', 'customer_id');
    }

}

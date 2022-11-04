<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';

    public function Runs()
    {
        return $this->hasMany('App\Runs', 'order_id', 'id');
    }

    public function Customer()
    {
        return $this->hasOne('App\Customers', 'id', 'customer_id');
    }

}

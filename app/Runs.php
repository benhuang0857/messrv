<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Runs extends Model
{
    protected $table = 'runs';

    public function Products()
    {
        return $this->hasOne('App\Products', 'id', 'product_id');
    }

    public function Maker()
    {
        return $this->hasOne('App\User', 'id', 'maker_id');
    }

    public function Order()
    {
        return $this->hasOne('App\Orders', 'id', 'order_id');
    }
}

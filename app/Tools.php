<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tools extends Model
{
    protected $table = 'tools';

    public function Vendors()
    {
        return $this->hasOne('App\Vendors', 'id', 'vendor_id');
    }
}

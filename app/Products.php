<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';

    public function Models()
    {
        return $this->hasOne('App\Models', 'id', 'model_id');
    }
}

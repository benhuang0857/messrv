<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Steps extends Model
{
    protected $table = 'steps';

    public function Tools()
    {
        return $this->hasOne('App\Tools', 'id', 'tool_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProdProcessesList extends Model
{
    protected $table = 'prod_processes_list';

    public function Products()
    {
        return $this->hasOne('App\Products', 'id', 'product_id');
    }

    public function Processes()
    {
        return $this->hasOne('App\Processes', 'id', 'process_id');
    }
}

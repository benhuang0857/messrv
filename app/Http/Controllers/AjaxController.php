<?php

namespace App\Http\Controllers;

use App\ProdProcessesList;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function PredictTime(Request $req)
    {
        $prodProcessesList = ProdProcessesList::where('product_id', $req->pid)->get();
        $prdiect_second = 0;
        foreach ($prodProcessesList as $item) {
            $prdiect_second += $item->process_time;
        }
        
        return json_encode($prdiect_second);
    }
}

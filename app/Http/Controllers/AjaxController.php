<?php

namespace App\Http\Controllers;

use App\ProdProcessesList;
use App\Batches;
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

    public function ProcessStart(Request $req)
    {
        $batch = Batches::where('id', $req->batchId)->first();
        $batch->start_time = date('Y-m-d H:i:s');
        $batch->state = "process";
        $batch->save();

        return json_encode('Start');
    }

    public function ProcessHold(Request $req)
    {
        $batch = Batches::where('id', $req->batchId)->first();
        $batch->end_time = date('Y-m-d H:i:s');
        $batch->state = "hold";
        $batch->save();

        return json_encode('End');
    }

    public function ProcessComplete(Request $req)
    {
        $batch = Batches::where('id', $req->batchId)->first();
        $batch->end_time = date('Y-m-d H:i:s');
        $batch->state = "complete";
        $batch->save();

        return json_encode('Complete');
    }
}

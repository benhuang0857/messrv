<?php

namespace App\Http\Controllers;

use App\ProdProcessesList;
use App\Batches;
use App\BatchStateRecord;
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
        $batch->tool = $req->tool;
        $batch->doer_id = $req->doer_id;
        $batch->state = "process";
        $batch->save();

        $batchStateRecord = new BatchStateRecord();
        $batchStateRecord->batch_id = $req->batchId;
        $batchStateRecord->tool = $req->tool;
        $batchStateRecord->user_id = $req->doer_id;
        $batchStateRecord->state = "process";
        $batchStateRecord->note = 'process';
        $batchStateRecord->save();

        // $batch->start_time = date('Y-m-d H:i:s');
        // $batch->state = "process";
        
        return json_encode('Start');
    }

    public function ProcessHold(Request $req)
    {
        $batch = Batches::where('id', $req->batchId)->first();
        // $batch->end_time = date('Y-m-d H:i:s');
        $batch->state = "hold";
        $batch->save();
        
        $batchStateRecord = new BatchStateRecord();
        $batchStateRecord->batch_id = $req->batchId;
        $batchStateRecord->tool = $req->tool;
        $batchStateRecord->user_id = $req->doer_id;
        $batchStateRecord->state = "hold";
        $batchStateRecord->note = $req->holdReason;
        $batchStateRecord->save();

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

<?php

namespace App\Http\Controllers;

use App\ProdProcessesList;
use App\Batches;
use App\BatchStateRecord;
use Illuminate\Http\Request;

use Auth;

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
        $canRun = false;
        
        if (Auth::user()->id != NULL) {
            if (Auth::user()->id == $req->doer_id) {
                $canRun = true;
            }else {
                $canRun = false;
            }
        }else {
            $canRun = true;
        }

        if ($canRun) {
            $batchStateRecord = new BatchStateRecord();
            $batchStateRecord->batch_id = $req->batchId;
            $batchStateRecord->tool = $req->tool;
            $batchStateRecord->user_id = $req->doer_id;
            $batchStateRecord->state = "process";
            $batchStateRecord->note = '進行中';
            $batchStateRecord->save();

            $batch = Batches::where('id', $req->batchId)->first();
            $batch->start_time = BatchStateRecord::where('batch_id', $req->batchId)
                                                    ->where('state', 'process')
                                                    ->first()->created_at;
            $batch->tool = $req->tool;
            $batch->doer_id = $req->doer_id;
            $batch->state = "process";
            $batch->save();

            return json_encode('ok');
        }else {
            return json_encode('ng');
        }     
    }

    public function StartProcessHold(Request $req)
    {
        $batch = Batches::where('id', $req->batchId)->first();
        // $batch->end_time = date('Y-m-d H:i:s');
        $batch->state = "starthold";
        $batch->save();
        
        $batchStateRecord = new BatchStateRecord();
        $batchStateRecord->batch_id = $req->batchId;
        $batchStateRecord->tool = $req->tool;
        $batchStateRecord->user_id = $req->doer_id;
        $batchStateRecord->state = "starthold";
        $batchStateRecord->note = $req->holdReason;
        $batchStateRecord->save();

        return json_encode('StartHold');
    }

    public function EndProcessHold(Request $req)
    {
        $batch = Batches::where('id', $req->batchId)->first();
        // $batch->end_time = date('Y-m-d H:i:s');
        $batch->state = "process";
        $batch->save();
        
        $batchStateRecord = new BatchStateRecord();
        $batchStateRecord->batch_id = $req->batchId;
        $batchStateRecord->tool = $req->tool;
        $batchStateRecord->user_id = $req->doer_id;
        $batchStateRecord->state = "endhold";
        $batchStateRecord->note = $req->holdReason;
        $batchStateRecord->save();

        return json_encode('EndHold');
    }

    public function ProcessComplete(Request $req)
    {
        $batch = Batches::where('id', $req->batchId)->first();

        $record = new BatchStateRecord();
        $batchStateRecord = new BatchStateRecord();
        $batchStateRecord->batch_id = $req->batchId;
        $batchStateRecord->tool = $req->tool;
        $batchStateRecord->user_id = $req->doer_id;
        $batchStateRecord->state = "complete";
        $batchStateRecord->note = "complete";
        $batchStateRecord->save();

        $firstProcessRecord = BatchStateRecord::where('batch_id', $req->batchId)
                                            ->where('state', 'process')->first();
        $firstCompleteRecord = BatchStateRecord::where('batch_id', $req->batchId)
                                            ->where('state', 'complete')->first();
        $batch->start_time = $firstProcessRecord->created_at;
        $batch->end_time = $firstCompleteRecord->created_at;
        $interval = strtotime($firstCompleteRecord->created_at) - strtotime($firstProcessRecord->created_at);
        $batch->run_second = $interval;
        $batch->state = "complete";
        $batch->save();

        return json_encode('Complete');
    }

    public function ChangeHoldReason(Request $req)
    {
        $batchStateRecord = BatchStateRecord::where('id', $req->records_id)->first();
        $batchStateRecord->note = $req->change_hold_reason;
        $batchStateRecord->save();

        return json_encode('Complete');
    }
}

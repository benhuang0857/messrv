<?php

namespace App\Admin\Extensions;

use App\Batches;
use App\BatchStateRecord;
use App\Processes;
use App\Products;
use App\ProdProcessesList;
use App\User;
use Encore\Admin\Grid\Exporters\ExcelExporter;
use Maatwebsite\Excel\Concerns\WithMapping;

class BatchExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = '工單.xlsx';

    protected $columns = [
        'id' => 'ID',
        'batch_code' => '批號',
        'run_id' => '工單',
        'ProdProcessesList.order' => '製程順序',
        'ProdProcessesList.id' => '製程與產品',
        'doer_id' => '員工',
        'quantity' => '數量',
        // 'scrap' => '報廢',
        'start_time' => '開始時間',
        'end_time' => '結束時間',
        'run_second' => '總時間',
        'scrap' => '總休息時間',
        'RealTime' => '實際時間',
        'PiceTime' => '單位工時',
        'ProdProcessesList.process_time' => '標準工時',
        'DiffTime' => '超前工時',
        'area' => '負責區域/部門',
        'state' => '狀態',
    ];

    public function map($row) : array
    {
        $start_time = $row->start_time;
        $end_time = $row->end_time;
        $prod_processes_name = '--';
        $total_rest = '--';
        $real_time = '--';
        $pice_time = '--';
        $diff_time = '--';
        $area = '--';

        if ($start_time == '1000-01-01 00:00:00')
            $start_time = '--';
        if ($end_time == '1000-01-01 00:00:00')
            $end_time = '--';

        $prodProcessesList = ProdProcessesList::where('id', Batches::where('id', $row->id)->first()->ProdProcessesList->id)->first();
        $processId = $prodProcessesList->process_id;
        $productId = $prodProcessesList->product_id;
        $processesName = Processes::where('id', $processId)->first()->name;
        $productsName = Products::where('id', $productId)->first()->product_code;
        $prod_processes_name = $processesName.'-'.$productsName;

        // 總休息時間
        try {
            $startrecords = BatchStateRecord::where('batch_id', $row->id)
                                            ->where('state', 'starthold')->get();
            $eedrecords = BatchStateRecord::where('batch_id', $row->id)
                                            ->where('state', 'endhold')->get();
            $restSec = 0;
            for ($i=0; $i < sizeof($startrecords); $i++) { 
                $start = $startrecords[$i]->created_at;
                $end = $eedrecords[$i]->created_at;
                $restSec += $start->diffInSeconds($end);
            }

            $total_rest = $restSec;
        } catch (\Throwable $th) {
            $total_rest = "--";
        }

        // 實際時間
        try {
            $batch_id = $row->id;
            $run_second = Batches::where('id', $batch_id)->first()->run_second;

            $startrecords = BatchStateRecord::where('batch_id', $batch_id)
                                            ->where('state', 'starthold')->get();
            $eedrecords = BatchStateRecord::where('batch_id', $batch_id)
                                            ->where('state', 'endhold')->get();
            $restSec = 0;
            for ($i=0; $i < sizeof($startrecords); $i++) { 
                $start = $startrecords[$i]->created_at;
                $end = $eedrecords[$i]->created_at;
                $restSec += $start->diffInSeconds($end);
            }

            $real_time = ($run_second - $restSec);
        } catch (\Throwable $th) {
            $real_time = "--";
        }

        // 單位工時
        try {
            $batch_id = $row->id;
            $pice = Batches::where('id', $batch_id)->first()->quantity;
            $run_second = Batches::where('id', $batch_id)->first()->run_second;

            $startrecords = BatchStateRecord::where('batch_id', $batch_id)
                                            ->where('state', 'starthold')->get();
            $eedrecords = BatchStateRecord::where('batch_id', $batch_id)
                                            ->where('state', 'endhold')->get();
            $restSec = 0;
            for ($i=0; $i < sizeof($startrecords); $i++) { 
                $start = $startrecords[$i]->created_at;
                $end = $eedrecords[$i]->created_at;
                $restSec += $start->diffInSeconds($end);
            }

            $reusltTime = ($run_second - $restSec)/$pice;
            $pice_time = round($reusltTime, 2);
        } catch (\Throwable $th) {
            $pice_time = "--";
        }

        // 超前工時
        try {
            $batch_id = $row->id;
            $ppId = Batches::where('id', $batch_id)->first()->prod_processes_list_id;
            $process_time = ProdProcessesList::where('id', $ppId)->first()->process_time;
            $pice = Batches::where('id', $batch_id)->first()->quantity;
            $run_second = Batches::where('id', $batch_id)->first()->run_second;

            $startrecords = BatchStateRecord::where('batch_id', $batch_id)
                                            ->where('state', 'starthold')->get();
            $eedrecords = BatchStateRecord::where('batch_id', $batch_id)
                                            ->where('state', 'endhold')->get();
            $restSec = 0;
            for ($i=0; $i < sizeof($startrecords); $i++) { 
                $start = $startrecords[$i]->created_at;
                $end = $eedrecords[$i]->created_at;
                $restSec += $start->diffInSeconds($end);
            }

            $reusltTime = round(($process_time*$row->quantity - $run_second), 2);
            $diff_time = $reusltTime;
        } catch (\Throwable $th) {
            $diff_time = "--";
        }

        // 負責區域/部門
        $dep = Department::where('id', $row->area)->first();
            $area = $dep->name;

        // 狀態
        $stateArr = [
            'pending'  => '確認中', 
            'approve'   => '等待加工',
            'disapprove'=> '取消加工',
            'process'   => '加工中',
            'complete'  => '已完成',
            'starthold' => '開始暫停',
            'endhold'   => '結束暫停(執行中)',
            'cancel'    => '取消',
        ];

        return [
            $row->id,
            $row->batch_code,
            $row->run_id,
            Batches::where('id', $row->id)->first()->ProdProcessesList->order,
            $prod_processes_name,
            ($row->doer_id==null)?'尚未指派':User::where('id', $row->doer_id)->first()->name,
            $row->quantity,
            // $row->scrap,
            $start_time,
            $end_time,
            $row->run_second,
            $total_rest,
            $real_time,
            $pice_time,
            Batches::where('id', $row->id)->first()->ProdProcessesList->process_time*$row->quantity,
            $diff_time,
            $area,
            $row->state,
        ];
    }
}
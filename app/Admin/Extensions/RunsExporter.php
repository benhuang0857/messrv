<?php

namespace App\Admin\Extensions;

use App\Batches;
use App\BatchStateRecord;
use App\Processes;
use App\Products;
use App\ProdProcessesList;
use App\Department;
use App\User;
use App\Runs;
use Encore\Admin\Grid\Exporters\ExcelExporter;
use Maatwebsite\Excel\Concerns\WithMapping;

class RunsExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = '工單管理.xlsx';

    protected $columns = [
        'id' => 'ID',
        'run_code' => '工單ID',
        'Maker.name' => '建立者',
        'Products.product_code' => '產品',
        'quantity' => '總數量',
        'each_quantity' => '分批',
        'start_time' => '開始時間',
        'end_time' => '結束時間',
        'predict_second' => '預估執行秒數',
        'run_second' => '實際執行秒數',
        'qtime' => '限制秒數(QTime)',
        'state' => '狀態'
    ];

    public function map($row) : array
    {
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
            $row->run_code,
            Runs::where('id', $row->id)->first()->Maker->name,
            Runs::where('id', $row->id)->first()->Products->product_code,
            $row->quantity,
            $row->each_quantity,
            $row->start_time,
            $row->end_time,
            $row->predict_second,
            $row->run_second==null ? '0':$row->run_second,
            $row->qtime,
            $row->state,
        ];
    }
}
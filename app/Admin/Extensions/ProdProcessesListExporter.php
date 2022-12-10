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

class ProdProcessesListExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = '產品生產流程.xlsx';

    protected $columns = [
        'id' => 'Id',
        'Products.product_code' => '產品代碼',
        'Processes.process_code' => '製程代碼',
        'order' => '排序',
        'department' => '部門',
        'process_time' => '執行秒數',
        'min_slot' => '最少執行數量',
        'max_slot' => '最多執行數量',
        'state' => '狀態',
    ];

    public function map($row) : array
    {
        return [
            $row->company_name,
            ProdProcessesList::where('id', $row->id)->first()->Products->product_code,
            ProdProcessesList::where('id', $row->id)->first()->Processes->process_code,
            $row->order,
            $row->department,
            $row->process_time,
            $row->min_slot,
            $row->max_slot,
            $row->state,
        ];
    }
}
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

class ProcessesExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = '製程.xlsx';

    protected $columns = [
        'id' => 'Id',
        'process_code' => '製程代碼',
        'name' => '製程名稱',
        'process_time' => '製程秒數',
        'note' => '備註',
    ];

    public function map($row) : array
    {
        return [
            $row->id,
            $row->process_code,
            $row->name,
            $row->process_time,
            $row->note,
        ];
    }
}
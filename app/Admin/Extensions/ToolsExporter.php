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
use App\Tools;
use Encore\Admin\Grid\Exporters\ExcelExporter;
use Maatwebsite\Excel\Concerns\WithMapping;

class ToolsExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = '機台.xlsx';

    protected $columns = [
        'id' => 'Id',
        'Vendors.company_name' => '供應商',
        'name' => '機台名稱',
        'area' => '位置',
        'note' => '備註',
    ];

    public function map($row) : array
    {
        return [
            $row->id,
            Tools::where('id', $row->id)->first()->Vendors->company_name,
            $row->name,
            $row->area,
            $row->note,
        ];
    }
}
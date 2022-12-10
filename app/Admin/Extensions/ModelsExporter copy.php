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

class ModelsExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = '規格.xlsx';

    protected $columns = [
        'id' => 'Id',
        'model_code' => '規格代碼',
        'model_name' => '規格名稱',
        'spec' => 'SPEC',
        'note' => '備註',
    ];

    public function map($row) : array
    {
        return [
            $row->id,
            $row->model_code,
            $row->model_name,
            $row->spec,
            $row->note,
        ];
    }
}
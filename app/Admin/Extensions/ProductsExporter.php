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

class ProductsExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = '產品.xlsx';

    protected $columns = [
        'id' => 'ID',
        'Models.model_code' => '規格代碼',
        'product_code' => '產品代碼',
        'product_name' => '產品名稱',
        'note' => '備註',
    ];

    public function map($row) : array
    {
        return [
            $row->id,
            Products::where('id', $row->id)->first()->Models->model_code,
            $row->product_code,
            $row->product_name,
            $row->note,
        ];
    }
}
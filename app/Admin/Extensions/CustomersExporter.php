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

class CustomersExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = '客戶.xlsx';

    protected $columns = [
        'company_name' => '公司',
        'contact_name' => '客戶名',
        'phone' => '市話',
        'mobile' => '手機',
        'gui_number' => '統編',
        'address' => '地址',
        'note' => '備註',
        'created_at' => '建立時間',
    ];

    public function map($row) : array
    {
        return [
            $row->company_name,
            $row->contact_name,
            $row->phone,
            $row->mobile,
            $row->gui_number,
            $row->address,
            $row->note,
            $row->created_at,
        ];
    }
}
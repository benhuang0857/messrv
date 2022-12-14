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
use App\Customers;
use Encore\Admin\Grid\Exporters\ExcelExporter;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = '訂單.xlsx';

    protected $columns = [
        'order_code' => '訂單號',
        'customer_id' => '廠商',
        'status' => '狀態',
        'created_at' => '建立時間',
    ];

    public function map($row) : array
    {
        $customer = Customers::where('id', $row->customer_id)->first();

        return [
            $row->order_code,
            $customer->company_name,
            $row->customer_id,
            $row->status,
            $row->created_at,
        ];
    }
}
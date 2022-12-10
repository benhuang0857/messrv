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

class UserExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = '使用者.xlsx';

    protected $columns = [
        'line_id' => 'Line Id',
        'employee_id' => '工號',
        'email' => 'Email',
        'name' => '姓名',
        'department' => '部門',
        'job_title' => '職稱',
        'gender' => '性別',
        'state' => '狀態',
        'note' => '備註',
        'created_at' => '建立時間',
    ];

    public function map($row) : array
    {
        return [
            $row->line_id,
            $row->employee_id,
            $row->email,
            $row->name,
            $row->department,
            $row->job_title,
            $row->gender,
            $row->state,
            $row->note,
            $row->created_at,
        ];
    }
}
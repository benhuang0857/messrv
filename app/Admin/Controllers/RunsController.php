<?php

namespace App\Admin\Controllers;

use App\Runs;
use App\Batches;
use App\Staffs;
use App\Products;
use App\ProdProcessesList;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class RunsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Runs';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Runs());

        $grid->column('id', __('Id'));
        $grid->column('run_code', __('Run code'));
        $grid->column('maker_id', __('Maker id'));
        $grid->column('product_id', __('Product id'));
        $grid->column('quantity', __('Quantity'));
        $grid->column('each_quantity', __('Each quantity'));
        $grid->column('start_time', __('Start time'));
        $grid->column('end_time', __('End time'));
        $grid->column('run_second', __('Run second'));
        $grid->column('state', __('State'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Runs::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('run_code', __('Run code'));
        $show->field('maker_id', __('Maker id'));
        $show->field('product_id', __('Product id'));
        $show->field('quantity', __('Quantity'));
        $show->field('each_quantity', __('Each quantity'));
        $show->field('start_time', __('Start time'));
        $show->field('end_time', __('End time'));
        $show->field('run_second', __('Run second'));
        $show->field('state', __('State'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Runs());

        $_staffs = Staffs::all();
        $_staffMap = array();
        foreach($_staffs as $item)
        {
            $_staffMap[$item->id] = $item->name.'('.$item->employee_id.')';
        }

        $_products = Products::all();
        $_producMap = array();
        foreach($_products as $item)
        {
            $_producMap[$item->id] = $item->product_code;
        }

        $form->text('run_code', __('Run code'))->default('run-'.uniqid());
        $form->select('maker_id', __('Maker id'))->options($_staffMap);
        $form->select('product_id', __('Product id'))->options($_producMap);
        $form->number('quantity', __('Quantity'))->default(1);
        $form->number('each_quantity', __('Each quantity'))->default(1);
        $form->datetime('start_time', __('Start time'))->default(date('Y-m-d H:i:s'));
        $form->datetime('end_time', __('End time'))->default(date('Y-m-d H:i:s'));
        $form->number('run_second', __('Run second'))->default(1);
        $form->select('state', __('State'))->default('peddning')->options([
            'peddning'  => '尚未審核', 
            'approve'   => '審核通過',
            'disapprove'=> '審核未通過',
            'process'   => '進行中',
            'complete'  => '完成',
            'hold'      => '暫停',
            'cancel'    => '取消',
        ]);

        $form->saving(function (Form $form) {
            if ($form->state == 'process') {
                $ProdProcessesList = ProdProcessesList::where('product_id', $form->product_id)
                                                        ->orderBy('order', 'asc')
                                                        ->get();

                foreach ($ProdProcessesList as $key => $process) {

                    $quantity = $form->quantity;
                    $each_quantity = $form->each_quantity;
                    $count = $quantity/$each_quantity;

                    for ($i=0; $i < $count; $i++) { 
                        $batch = new Batches();
                        $batch->batch_code = 'batch-'.uniqid();
                        $batch->run_id = $form->run_code;
                        $batch->prod_processes_list_id = $process->id;
                        $batch->doer_id = $form->maker_id;
                        $batch->quantity = $each_quantity;
                        $batch->start_time = $form->start_time;
                        $batch->end_time = $form->end_time;
                        $batch->run_second = $form->run_second;
                        $batch->state = 'pedding';
                        $batch->save();
                    }
                }
            }

            
        });

        return $form;
    }
}

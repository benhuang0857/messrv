<?php

namespace App\Admin\Controllers;

use App\Batches;
use App\Products;
use App\Processes;
use App\User;
use App\ProdProcessesList;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BatchesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '生產履歷(Batches)';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Batches());

        // $grid->column('id', __('Id'));
        $grid->column('batch_code', __('批號'));
        $grid->column('run_id', __('工單'));
        $grid->column('ProdProcessesList.order', __('製程順序'));
        $grid->column('ProdProcessesList.id', __('製程與產品'))->display(function($id){
            $prodProcessesList = ProdProcessesList::where('id', $id)->first();
            $processId = $prodProcessesList->process_id;
            $productId = $prodProcessesList->product_id;
            $processesName = Processes::where('id', $processId)->first()->name;
            $productsName = Products::where('id', $productId)->first()->product_code;
            return $processesName.'-'.$productsName;
        });
        $grid->column('doer_id', __('員工'))->display(function($id){
            $staff = User::where('id', $id)->first();
            return $staff->name.'('.$staff->employee_id.')';
        });
        $grid->column('quantity', __('數量'));
        $grid->column('scrap', __('報廢'));
        $grid->column('start_time', __('開始時間'));
        $grid->column('end_time', __('結束時間'));
        $grid->column('run_second', __('執行時間'));
        $grid->column('state', __('狀態'));
        // $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Batches::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('batch_code', __('Batch code'));
        $show->field('run_id', __('Run id'));
        $show->field('prod_processes_list_id', __('Prod processes list id'));
        $show->field('doer_id', __('Doer id'));
        $show->field('quantity', __('Quantity'));
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
        $form = new Form(new Batches());

        $form->text('batch_code', __('批號'));
        $form->text('run_id', __('工單'));
        $form->text('prod_processes_list_id', __('製程與產品'));
        $form->text('doer_id', __('員工'));
        $form->number('quantity', __('數量'))->default(1);
        $form->number('scrap', __('報廢'))->default(0);
        $form->datetime('start_time', __('開始時間'))->default(date('Y-m-d H:i:s'));
        $form->datetime('end_time', __('結束時間'))->default(date('Y-m-d H:i:s'));
        $form->number('run_second', __('執行秒數'));
        $form->text('state', __('狀態'))->default('peddning');

        return $form;
    }
}

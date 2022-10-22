<?php

namespace App\Admin\Controllers;

use App\Batches;
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
    protected $title = 'Batches';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Batches());

        $grid->column('id', __('Id'));
        $grid->column('batch_code', __('Batch code'));
        $grid->column('run_id', __('Run id'));
        $grid->column('prod_processes_list_id', __('Prod processes list id'));
        $grid->column('doer_id', __('Doer id'));
        $grid->column('quantity', __('Quantity'));
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

        $form->text('batch_code', __('Batch code'));
        $form->text('run_id', __('Run id'));
        $form->text('prod_processes_list_id', __('Prod processes list id'));
        $form->text('doer_id', __('Doer id'));
        $form->number('quantity', __('Quantity'))->default(1);
        $form->datetime('start_time', __('Start time'))->default(date('Y-m-d H:i:s'));
        $form->datetime('end_time', __('End time'))->default(date('Y-m-d H:i:s'));
        $form->number('run_second', __('Run second'));
        $form->text('state', __('State'))->default('peddning');

        return $form;
    }
}

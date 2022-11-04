<?php

namespace App\Admin\Controllers;

use App\BatchStateRecord;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BatchStateRecordController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'BatchStateRecord';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BatchStateRecord());

        $grid->column('id', __('Id'));
        $grid->column('batch_id', __('Batch id'));
        $grid->column('tool', __('Tool'));
        $grid->column('user_id', __('User id'));
        $grid->column('state', __('State'));
        $grid->column('note', __('Note'));
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
        $show = new Show(BatchStateRecord::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('batch_id', __('Batch id'));
        $show->field('tool', __('Tool'));
        $show->field('user_id', __('User id'));
        $show->field('state', __('State'));
        $show->field('note', __('Note'));
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
        $form = new Form(new BatchStateRecord());

        $form->text('batch_id', __('Batch id'));
        $form->text('tool', __('Tool'));
        $form->text('user_id', __('User id'));
        $form->text('state', __('State'));
        $form->textarea('note', __('Note'));

        return $form;
    }
}

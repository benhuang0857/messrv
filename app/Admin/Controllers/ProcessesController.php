<?php

namespace App\Admin\Controllers;

use App\Processes;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProcessesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Processes';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Processes());

        $grid->column('id', __('Id'));
        $grid->column('process_code', __('Process code'));
        $grid->column('name', __('Name'));
        $grid->column('process_time', __('Process time'));
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
        $show = new Show(Processes::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('process_code', __('Process code'));
        $show->field('name', __('Name'));
        $show->field('process_time', __('Process time'));
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
        $form = new Form(new Processes());

        $form->text('process_code', __('Process code'));
        $form->text('name', __('Name'));
        $form->number('process_time', __('Process time'));
        $form->textarea('note', __('Note'));

        return $form;
    }
}

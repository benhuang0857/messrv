<?php

namespace App\Admin\Controllers;

use App\Models;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ModelsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Models';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Models());

        $grid->column('id', __('Id'));
        $grid->column('model_code', __('Model code'));
        $grid->column('model_name', __('Model name'));
        $grid->column('spec', __('Spec'));
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
        $show = new Show(Models::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('model_code', __('Model code'));
        $show->field('model_name', __('Model name'));
        $show->field('spec', __('Spec'));
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
        $form = new Form(new Models());

        $form->text('model_code', __('Model code'));
        $form->text('model_name', __('Model name'));
        $form->textarea('spec', __('Spec'));
        $form->textarea('note', __('Note'));

        return $form;
    }
}

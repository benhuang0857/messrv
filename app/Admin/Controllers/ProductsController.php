<?php

namespace App\Admin\Controllers;

use App\Products;
use App\Models;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Products';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Products());

        $grid->column('id', __('Id'));
        $grid->column('Models.model_code', __('Model Code'));
        $grid->column('product_code', __('Product code'));
        $grid->column('product_name', __('Product name'));
        $grid->column('pic_path', __('Pic path'));
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
        $show = new Show(Products::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('model_id', __('Model id'));
        $show->field('product_code', __('Product code'));
        $show->field('product_name', __('Product name'));
        $show->field('pic_path', __('Pic path'));
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
        $form = new Form(new Products());

        $_models = Models::all();
        $_modelMap = array();
        foreach($_models as $item)
        {
            $_modelMap[$item->id] = $item->model_code;
        }

        $form->select('model_id', __('Model id'))->options($_modelMap);
        $form->text('product_code', __('Product code'));
        $form->text('product_name', __('Product name'));
        $form->image('pic_path', __('Pic path'));
        $form->textarea('note', __('Note'));

        return $form;
    }
}

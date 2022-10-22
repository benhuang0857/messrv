<?php

namespace App\Admin\Controllers;

use App\ProdProcessesList;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProdProcessesListController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ProdProcessesList';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ProdProcessesList());

        $grid->column('id', __('Id'));
        $grid->column('Products.product_code', __('Product id'));
        $grid->column('Processes.process_code', __('Process id'));
        $grid->column('order', __('Order'));
        $grid->column('process_time', __('Process time'));
        $grid->column('min_slot', __('Min slot'));
        $grid->column('max_slot', __('Max slot'));
        $grid->column('state', __('State'))->display(function($state){
            return $state==1?'啟用':'停用';
        });
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
        $show = new Show(ProdProcessesList::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('product_id', __('Product id'));
        $show->field('process_id', __('Process id'));
        $show->field('order', __('Order'));
        $show->field('process_time', __('Process time'));
        $show->field('max_slot', __('Max slot'));
        $show->field('min_slot', __('Min slot'));
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
        $form = new Form(new ProdProcessesList());

        $form->text('product_id', __('Product id'));
        $form->text('process_id', __('Process id'));
        $form->number('order', __('Order'));
        $form->number('process_time', __('Process time'));
        $form->number('max_slot', __('Max slot'));
        $form->number('min_slot', __('Min slot'));
        $form->switch('state', __('State'));

        return $form;
    }
}

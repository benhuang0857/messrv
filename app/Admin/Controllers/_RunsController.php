<?php

namespace App\Admin\Controllers;

use App\Runs;
use App\Staffs;
use App\Products;
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
        $form->datetime('start_time', __('Start time'))->default(date('Y-m-d H:i:s'));
        $form->datetime('end_time', __('End time'))->default(date('Y-m-d H:i:s'));
        $form->number('run_second', __('Run second'));
        $form->switch('state', __('State'));

        return $form;
    }
}

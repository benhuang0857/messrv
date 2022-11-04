<?php

namespace App\Admin\Controllers;

use App\Orders;
use App\Runs;
use App\Customers;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrdersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Orders';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Orders());

        $grid->column('id', __('Id'));
        $grid->column('order_code', __('Order code'));
        $grid->column('customer_id', __('Customer id'));
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
        $show = new Show(Orders::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('order_code', __('Order code'));
        $show->field('customer_id', __('Customer id'));
        $show->field('run_id', __('Run id'));
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
        $form = new Form(new Orders());

        $_customers = Customers::all();
        $_customerMap = array();
        foreach($_customers as $item)
        {
            $_customerMap[$item->id] = $item->company_name;
        }

        $_runs = Runs::all();
        $_runMap = array();
        foreach($_runs as $item)
        {
            $_runMap[$item->id] = $item->run_code;
        }

        $form->text('order_code', __('訂單號'))->default(uniqid());
        $form->select('customer_id', __('客戶'))->options($_customerMap);

        return $form;
    }
}

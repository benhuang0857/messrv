<?php

namespace App\Admin\Controllers;

use App\Customers;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CustomersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '客戶(Customers)';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Customers());

        // $grid->column('id', __('Id'));
        $grid->column('company_name', __('公司'));
        $grid->column('contact_name', __('客戶名'));
        $grid->column('phone', __('市話'));
        $grid->column('mobile', __('手機'));
        $grid->column('gui_number', __('統編'));
        $grid->column('address', __('地址'));
        $grid->column('note', __('備註'));
        $grid->column('created_at', __('建立時間'));
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
        $show = new Show(Customers::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('company_name', __('Company name'));
        $show->field('contact_name', __('Contact name'));
        $show->field('phone', __('Phone'));
        $show->field('mobile', __('Mobile'));
        $show->field('gui_number', __('Gui number'));
        $show->field('address', __('Address'));
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
        $form = new Form(new Customers());

        $form->text('company_name', __('Company name'));
        $form->text('contact_name', __('Contact name'));
        $form->mobile('phone', __('Phone'));
        $form->mobile('mobile', __('Mobile'));
        $form->text('gui_number', __('Gui number'));
        $form->text('address', __('Address'));
        $form->textarea('note', __('Note'));

        return $form;
    }
}

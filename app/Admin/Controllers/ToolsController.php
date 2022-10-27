<?php

namespace App\Admin\Controllers;

use App\Tools;
use App\Vendors;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ToolsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '機台(Tools)';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Tools());

        $grid->column('id', __('Id'));
        $grid->column('Vendors.company_name', __('供應商'));
        // $grid->column('vendor_id', __('Vendor id'));
        $grid->column('name', __('機台名稱'));
        $grid->column('area', __('位置'));
        $grid->column('note', __('備註'));
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
        $show = new Show(Tools::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('vendor_id', __('Vendor id'));
        $show->field('name', __('Name'));
        $show->field('area', __('Area'));
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
        $form = new Form(new Tools());

        $_vendors = Vendors::all();
        $_vendorMap = array();
        foreach($_vendors as $item)
        {
            $_vendorMap[$item->id] = $item->company_name;
        }

        $form->select('vendor_id', __('Vendor Name'))->options($_vendorMap);
        $form->text('name', __('Name'));
        $form->text('area', __('Area'));
        $form->textarea('note', __('Note'));

        return $form;
    }
}

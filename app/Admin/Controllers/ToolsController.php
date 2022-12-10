<?php

namespace App\Admin\Controllers;

use App\Tools;
use App\Vendors;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Admin;

use App\Admin\Extensions\ToolsExporter;

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

        $grid->exporter(new ToolsExporter());

        $grid->column('id', __('<a href="#">Id▼</a>'));
        $grid->column('Vendors.company_name', __('<a href="#">供應商▼</a>'));
        // $grid->column('vendor_id', __('Vendor id'));
        $grid->column('name', __('<a href="#">機台名稱▼</a>'));
        $grid->column('area', __('<a href="#">位置▼</a>'));
        $grid->column('note', __('<a href="#">備註▼</a>'));
        // $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));

        Admin::html('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.0.10/js/jquery.tablesorter.min.js" integrity="sha512-r8Bn3mRanym3q+4Xvnmt3Wjp8LzovdGYgEksa0NuUzg6D8wKkRM7riZzHZs31yJcGb1NeBZ0aEE6HEsScACstw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script type="text/javascript">
            $(".grid-table").tablesorter();
        </script>
        ');

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

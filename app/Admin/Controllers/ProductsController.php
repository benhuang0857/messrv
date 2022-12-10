<?php

namespace App\Admin\Controllers;

use App\Products;
use App\Models;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Admin;

use App\Admin\Extensions\ProductsExporter;

class ProductsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '產品(Products)';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Products());

        $grid->exporter(new ProductsExporter());

        $grid->column('id', __('<a href="#">Id▼</a>'));
        $grid->column('Models.model_code', __('<a href="#">規格代碼▼</a>'));
        $grid->column('product_code', __('<a href="#">產品代碼▼</a>'));
        $grid->column('product_name', __('<a href="#">產品名稱▼</a>'));
        $grid->column('pic_path', __('<a href="#">產品圖片▼</a>'));
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

        $form->select('model_id', __('規格代碼'))->options($_modelMap);
        $form->text('product_code', __('產品代碼'));
        $form->text('product_name', __('產品名稱'));
        $form->image('pic_path', __('產品圖片'));
        $form->textarea('note', __('備註'));

        return $form;
    }
}

<?php

namespace App\Admin\Controllers;

use App\Customers;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Admin;

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
        $grid->column('company_name', __('<a href="#">公司▼</a>'));
        $grid->column('contact_name', __('<a href="#">客戶名▼</a>'));
        $grid->column('phone', __('<a href="#">市話▼</a>'));
        $grid->column('mobile', __('<a href="#">手機▼</a>'));
        $grid->column('gui_number', __('<a href="#">統編▼</a>'));
        $grid->column('address', __('<a href="#">地址▼</a>'));
        $grid->column('note', __('<a href="#">備註▼</a>'));
        $grid->column('created_at', __('<a href="#">建立時間▼</a>'));
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

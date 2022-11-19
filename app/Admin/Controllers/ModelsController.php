<?php

namespace App\Admin\Controllers;

use App\Models;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Admin;

class ModelsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '規格(Models)';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Models());

        $grid->column('id', __('<a href="#">Id▼</a>'));
        $grid->column('model_code', __('<a href="#">規格代碼▼</a>'));
        $grid->column('model_name', __('<a href="#">規格名稱▼</a>'));
        $grid->column('spec', __('<a href="#">SPEC▼</a>'));
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

        $form->text('model_code', __('規格代碼'));
        $form->text('model_name', __('規格名稱'));
        $form->textarea('spec', __('SPEC'));
        $form->textarea('note', __('備註'));

        return $form;
    }
}

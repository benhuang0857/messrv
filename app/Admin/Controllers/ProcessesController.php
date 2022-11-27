<?php

namespace App\Admin\Controllers;

use App\Processes;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Admin;

class ProcessesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '製程(Processes)';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Processes());

        $grid->column('id', __('<a href="#">Id▼</a>'));
        $grid->column('process_code', __('<a href="#">製程代碼▼</a>'));
        $grid->column('name', __('<a href="#">製程名稱▼</a>'));
        $grid->column('process_time', __('<a href="#">製程秒數▼</a>'));
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
        $show = new Show(Processes::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('process_code', __('Process code'));
        $show->field('name', __('Name'));
        $show->field('process_time', __('Process time'));
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
        $form = new Form(new Processes());

        $form->text('process_code', __('製程代碼'));
        $form->text('name', __('製程名稱'));
        $form->number('process_time', __('製程秒數'))->default(0);
        $form->textarea('note', __('備註'));

        return $form;
    }
}

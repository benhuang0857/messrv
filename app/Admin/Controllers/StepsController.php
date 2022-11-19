<?php

namespace App\Admin\Controllers;

use App\Steps;
use App\Tools;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Admin;

class StepsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '步驟(Steps)';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Steps());

        $grid->column('id', __('<a href="#">Id▼</a>'));
        $grid->column('Tools.name', __('<a href="#">機台名稱▼</a>'));
        // $grid->column('tool_id', __('Tool id'));
        $grid->column('name', __('<a href="#">步驟名稱▼</a>'));
        $grid->column('max_slot', __('<a href="#">最大量▼</a>'));
        $grid->column('min_slot', __('<a href="#">最小量▼</a>'));
        $grid->column('step_time', __('<a href="#">秒數▼</a>'));
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
        $show = new Show(Steps::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('tool_id', __('Tool id'));
        $show->field('name', __('Name'));
        $show->field('max_slot', __('Max slot'));
        $show->field('min_slot', __('Min slot'));
        $show->field('step_time', __('Step time'));
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
        $form = new Form(new Steps());

        $_tools = Tools::all();
        $_toolMap = array();
        foreach($_tools as $item)
        {
            $_toolMap[$item->id] = $item->name;
        }

        $form->select('tool_id', __('Tool Name'))->options($_toolMap);
        $form->text('name', __('Name'));
        $form->number('max_slot', __('Max slot'));
        $form->number('min_slot', __('Min slot'));
        $form->number('step_time', __('Step time'));
        $form->textarea('note', __('Note'));

        return $form;
    }
}

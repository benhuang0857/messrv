<?php

namespace App\Admin\Controllers;

use App\ProdProcessesList;
use App\Products;
use App\Processes;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Admin;

class ProdProcessesListController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '產品生產流程(ProdProcessesList)';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ProdProcessesList());

        $grid->column('id', __('<a href="#">Id▼</a>'));
        $grid->column('Products.product_code', __('<a href="#">產品代碼▼</a>'));
        $grid->column('Processes.process_code', __('<a href="#">製程代碼▼</a>'));
        $grid->column('order', __('<a href="#">排序▼</a>'));
        $grid->column('process_time', __('<a href="#">執行秒數▼</a>'));
        $grid->column('min_slot', __('<a href="#">最少執行數量▼</a>'));
        $grid->column('max_slot', __('<a href="#">最多執行數量▼</a>'));
        $grid->column('state', __('<a href="#">狀態▼</a>'))->display(function($state){
            return $state==1?'啟用':'停用';
        });
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
        $show = new Show(ProdProcessesList::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('product_id', __('Product id'));
        $show->field('process_id', __('Process id'));
        $show->field('order', __('Order'));
        $show->field('process_time', __('Process time'));
        $show->field('max_slot', __('Max slot'));
        $show->field('min_slot', __('Min slot'));
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
        $form = new Form(new ProdProcessesList());

        $_products = Products::all();
        $_productMap = array();
        foreach($_products as $item)
        {
            $_productMap[$item->id] = $item->product_code;
        }

        $_processes = Processes::all();
        $_processMap = array();
        foreach($_processes as $item)
        {
            $_processMap[$item->id] = $item->process_code;
        }

        $form->select('product_id', __('產品代碼'))->options($_productMap);
        $form->select('process_id', __('製程代碼'))->options($_processMap);
        $form->number('order', __('排序'))->default(0);
        $form->number('process_time', __('執行秒數'))->default(0);
        $form->number('max_slot', __('最少執行數量'))->default(0);
        $form->number('min_slot', __('最多執行數量'))->default(0);
        $form->switch('state', __('狀態'));

        return $form;
    }
}

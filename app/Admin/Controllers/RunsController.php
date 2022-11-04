<?php

namespace App\Admin\Controllers;

use App\Runs;
use App\Orders;
use App\Batches;
use App\User;
use App\Products;
use App\ProdProcessesList;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Admin;

class RunsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '工單管理(Runs)';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Runs());

        $grid->column('id', __('Id'));
        $grid->column('run_code', __('工單ID'));
        $grid->column('Maker.name', __('建立者'));
        $grid->column('Products.product_code', __('產品'));
        $grid->column('quantity', __('總數量'));
        $grid->column('each_quantity', __('分批'));
        $grid->column('start_time', __('開始時間'));
        $grid->column('end_time', __('結束時間'));
        $grid->column('predict_second', __('預估執行秒數'))->display(function($time){
            return $time.'秒(約等於'.round(($time/60), 2).'分鐘)';
        });
        $grid->column('run_second', __('實際執行秒數'))->display(function($time){
            return $time.'秒(約等於'.round(($time/60), 2).'分鐘)';
        });
        $grid->column('qtime', __('限制秒數(QTime)'))->display(function($time){
            return $time.'秒(約等於'.round(($time/60), 2).'分鐘)';
        });
        $grid->column('state', __('狀態'))->display(function($state){
            return '<span class="badge badge-warning" style="background:red">'.$state.'</span>';
        });
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
        $show = new Show(Runs::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('run_code', __('Run code'));
        $show->field('maker_id', __('Maker id'));
        $show->field('product_id', __('Product id'));
        $show->field('quantity', __('Quantity'));
        $show->field('each_quantity', __('Each quantity'));
        $show->field('start_time', __('Start time'));
        $show->field('end_time', __('End time'));
        $show->field('run_second', __('Run second'));
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
        $form = new Form(new Runs());

        $_staffs = User::all();
        $_staffMap = array();
        foreach($_staffs as $item)
        {
            $_staffMap[$item->id] = $item->name.'('.$item->employee_id.')';
        }

        $_products = Products::all();
        $_producMap = array();
        foreach($_products as $item)
        {
            $_producMap[$item->id] = $item->product_code;
        }

        $_orders = Orders::all();
        $_orderMap = array();
        foreach($_orders as $item)
        {
            $_orderMap[$item->id] = $item->order_code;
        }

        Admin::script('
            $("select[name=product_id]").change(function(){
                var productId = $("select[name=product_id]").val();

                $.ajax({
                    type: "GET",
                    url: "/ajax/predicttime",
                    dataType: "json",
                    data:{pid: productId},
                    success: function (response) {
                        var q = $("input[name=quantity]").val();
                        $("input[name=predict_second]").val(response*q);
                    },
                    error: function (thrownError) {
                        console.log(thrownError);
                    }
                });
            });
        ');

        $form->select('order_id', __('訂單ID'))->options($_orderMap);
        $form->text('run_code', __('工單ID'))->default(uniqid());
        $form->select('maker_id', __('建立者'))->options($_staffMap);
        $form->select('product_id', __('產品'))->options($_producMap);
        $form->number('quantity', __('總數量'))->default(1);
        $form->number('each_quantity', __('分批'))->default(1);
        $form->datetime('start_time', __('開始時間'))->default(date('Y-m-d H:i:s'));
        $form->datetime('end_time', __('結束時間'))->default(date('Y-m-d H:i:s'));
        $form->number('predict_second', __('預測執行秒數'))->default(0);
        $form->number('run_second', __('實際執行秒數'))->default(1);
        $form->number('qtime', __('限制秒數(QTime)'))->default(0);
        $form->select('state', __('狀態'))->default('approve')->options([
            'pending'  => '確認中', 
            'approve'   => '等待加工',
            'disapprove'=> '取消加工',
            'process'   => '加工中',
            'complete'  => '已完成',
            'hold'      => '暫停',
            'cancel'    => '取消',
        ]);

        $form->saving(function (Form $form) {
            if ($form->state == 'approve') {
                $ProdProcessesList = ProdProcessesList::where('product_id', $form->product_id)
                                                        ->orderBy('order', 'asc')
                                                        ->get();

                foreach ($ProdProcessesList as $key => $process) {

                    $quantity = $form->quantity;
                    $each_quantity = $form->each_quantity;
                    $count = $quantity/$each_quantity;

                    for ($i=0; $i < $count; $i++) { 
                        $batch = new Batches();
                        $batch->batch_code = $form->run_code.'_'.$i;
                        $batch->run_id = $form->run_code;
                        $batch->prod_processes_list_id = $process->id;
                        // $batch->doer_id = $form->maker_id;
                        $batch->quantity = $each_quantity;
                        $batch->start_time = '1000-01-01 00:00:00';
                        $batch->end_time = '1000-01-01 00:00:00';
                        $batch->run_second = 0;
                        $batch->state = 'approve';
                        $batch->save();
                    }
                }
            }

            
        });

        return $form;
    }
}

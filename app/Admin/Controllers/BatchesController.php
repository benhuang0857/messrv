<?php

namespace App\Admin\Controllers;

use App\Batches;
use App\Products;
use App\Processes;
use App\User;
use App\ProdProcessesList;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class BatchesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '生產履歷(Batches)';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Batches());

        // $grid->column('id', __('Id'));
        $grid->column('batch_code', __('批號'))->expand(function ($model) {

            $batches = $model->Records()->get()->map(function ($batch) {
                return $batch->only(['tool', 'state', 'note', 'created_at']);
            });
        
            return new Table(['機台', '狀態', '原因', '時間戳'], $batches->toArray());
        });
        $grid->column('run_id', __('工單'));
        $grid->column('ProdProcessesList.order', __('製程順序'));
        $grid->column('ProdProcessesList.id', __('製程與產品'))->display(function($id){
            $prodProcessesList = ProdProcessesList::where('id', $id)->first();
            $processId = $prodProcessesList->process_id;
            $productId = $prodProcessesList->product_id;
            $processesName = Processes::where('id', $processId)->first()->name;
            $productsName = Products::where('id', $productId)->first()->product_code;
            return $processesName.'-'.$productsName;
        });
        $grid->column('doer_id', __('員工'))->display(function($id){
            if ($id != NULL) {
                $staff = User::where('id', $id)->first();
                return $staff->name.'('.$staff->employee_id.')';
            } else {
                return '尚未指派';
            }
            
        });
        $grid->column('quantity', __('數量'));
        $grid->column('scrap', __('報廢'));
        $grid->column('start_time', __('開始時間'))->display(function($start_time){
            if ($start_time == '1000-01-01 00:00:00') {
                return '--';
            }
            else
            {
                return $start_time;
            }
        });
        $grid->column('end_time', __('結束時間'))->display(function($start_time){
            if ($start_time == '1000-01-01 00:00:00') {
                return '--';
            }
            else
            {
                return $start_time;
            }
        });
        $grid->column('run_second', __('實際執行時間'))->display(function($time){
            return $time.'秒(約等於'.round(($time/60), 2).'分鐘)';
        });
        $grid->column('area', __('負責區域/部門'))->display(function($area){
            if ($area == NULL) {
                return '--';
            }
            else
            {
                return $area;
            }
            
        });
        $grid->column('state', __('狀態'))->display(function($state){
            $stateArr = [
                'pending'  => '確認中', 
                'approve'   => '等待加工',
                'disapprove'=> '取消加工',
                'process'   => '加工中',
                'complete'  => '已完成',
                'hold'      => '暫停',
                'cancel'    => '取消',
            ];

            return $stateArr[$state];
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
        $show = new Show(Batches::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('batch_code', __('Batch code'));
        $show->field('run_id', __('Run id'));
        $show->field('prod_processes_list_id', __('Prod processes list id'));
        $show->field('doer_id', __('Doer id'));
        $show->field('quantity', __('Quantity'));
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
        $form = new Form(new Batches());

        $_users = User::all();
        $_userMap = array();
        foreach($_users as $item)
        {
            $_userMap[$item->id] = $item->name.'('.$item->employee_id.')';
        }

        $_prodProcessesList = ProdProcessesList::all();
        $_prodProcessesListMap = array();
        foreach($_prodProcessesList as $item)
        {
            $_prodProcessesListMap[$item->id] = $item->Processes->process_code.'('.$item->Products->product_name.')';
        }

        $form->text('batch_code', __('批號'))->readonly();
        $form->text('run_id', __('工單'))->readonly();
        $form->select('prod_processes_list_id', __('製程與產品'))->options($_prodProcessesListMap)->readonly();
        $form->select('doer_id', __('員工'))->options($_userMap);
        $form->text('area', __('負責區域/部門'));
        $form->number('quantity', __('數量'))->default(1);
        $form->number('scrap', __('報廢'))->default(0);
        $form->datetime('start_time', __('開始時間'))->default(date('Y-m-d H:i:s'));
        $form->datetime('end_time', __('結束時間'))->default(date('Y-m-d H:i:s'));
        $form->number('run_second', __('執行秒數'));
        $form->text('state', __('狀態'))->default('peddning');

        return $form;
    }
}

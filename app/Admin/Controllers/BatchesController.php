<?php

namespace App\Admin\Controllers;

use App\Batches;
use App\BatchStateRecord;
use App\Products;
use App\Processes;
use App\User;
use App\ProdProcessesList;
use App\Department;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Admin;

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

        $grid->filter(function($filter){
            $_products = Products::all();
            $_productMap = array();
            foreach($_products as $item)
            {
                $_productMap[$item->id] = $item->product_name;
            }

            $filter->disableIdFilter();
            $filter->equal('ProdProcessesList.product_id', '產品')->select($_productMap);
            $filter->where(function ($query) {
                $query->where('batch_code', 'like', "%{$this->input}%");
            }, '批號');
        });

        $grid->column('batch_code', __('<a href="#">批號▼</a>'))->expand(function ($model) {
            $record = array();
            $startrecords = $model->Records()->where('state', 'starthold')->get();
            $eedrecords = $model->Records()->where('state', 'endhold')->get();
            
            try {
                for ($i=0; $i < sizeof($startrecords); $i++) { 
                    $start = $startrecords[$i]->created_at;
                    $end = $eedrecords[$i]->created_at;
                    array_push($record, [
                        'start' => $start,
                        'end' => $end,
                        'note' => '<span class="badge badge-warning" style="background:red">'.$startrecords[$i]->note.'</span>',
                        'sum' => $start->diffInSeconds($end).'秒(約等於'.round((($start->diffInSeconds($end))/60), 2).'分鐘)',
                    ]);
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
            
            return new Table(['開始','結束', '原因','總工時'], $record);
        });
        $grid->column('run_id', __('<a href="#">工單▼</a>'));
        $grid->column('ProdProcessesList.order', __('<a href="#">製程順序▼</a>'));
        $grid->column('ProdProcessesList.id', __('<a href="#">製程與產品▼</a>'))->display(function($id){
            $prodProcessesList = ProdProcessesList::where('id', $id)->first();
            $processId = $prodProcessesList->process_id;
            $productId = $prodProcessesList->product_id;
            $processesName = Processes::where('id', $processId)->first()->name;
            $productsName = Products::where('id', $productId)->first()->product_code;
            return $processesName.'-'.$productsName;
        });
        $grid->column('doer_id', __('<a href="#">員工▼</a>'))->display(function($id){
            if ($id != NULL) {
                $staff = User::where('id', $id)->first();
                return $staff->name.'('.$staff->employee_id.')';
            } else {
                return '尚未指派';
            }
        });

        $qty = 0;
        $grid->column('quantity', __('<a href="#">數量▼</a>'))->display(function($quantity){
            $this->qty = $quantity;
            return $quantity;
        });
        $grid->column('scrap', __('<a href="#">報廢▼</a>'));
        $grid->column('start_time', __('<a href="#">開始時間▼</a>'))->display(function($start_time){
            if ($start_time == '1000-01-01 00:00:00') {
                return '--';
            }
            else
            {
                return $start_time;
            }
        });
        $grid->column('end_time', __('<a href="#">結束時間▼</a>'))->display(function($start_time){
            if ($start_time == '1000-01-01 00:00:00') {
                return '--';
            }
            else
            {
                return $start_time;
            }
        });
        $grid->column('run_second', __('<a href="#">總時間▼</a>'))->display(function($time){
            return $time.'秒(約等於'.round(($time/60), 2).'分鐘)';
        });

        $bid = null;

        $grid->column('id', __('<a href="#">總休息時間▼</a>'))->display(function($id){
            
            $this->bid = $id;
            
            try {

                $startrecords = BatchStateRecord::where('batch_id', $id)
                                                ->where('state', 'starthold')->get();
                $eedrecords = BatchStateRecord::where('batch_id', $id)
                                                ->where('state', 'endhold')->get();
                $restSec = 0;
                for ($i=0; $i < sizeof($startrecords); $i++) { 
                    $start = $startrecords[$i]->created_at;
                    $end = $eedrecords[$i]->created_at;
                    $restSec += $start->diffInSeconds($end);
                }

                return $restSec.'秒(約等於'.round(($restSec/60), 2).'分鐘)';
            } catch (\Throwable $th) {
                return "--";
            }
        });
        
        $grid->column('RealTime', __('<a href="#">實際時間▼</a>'))->display(function($Records){
            try {
                $batch_id = $this->bid;
                $run_second = Batches::where('id', $batch_id)->first()->run_second;

                $startrecords = BatchStateRecord::where('batch_id', $batch_id)
                                                ->where('state', 'starthold')->get();
                $eedrecords = BatchStateRecord::where('batch_id', $batch_id)
                                                ->where('state', 'endhold')->get();
                $restSec = 0;
                for ($i=0; $i < sizeof($startrecords); $i++) { 
                    $start = $startrecords[$i]->created_at;
                    $end = $eedrecords[$i]->created_at;
                    $restSec += $start->diffInSeconds($end);
                }

                return ($run_second - $restSec).'秒(約等於'.round((($run_second - $restSec)/60), 2).'分鐘)';
            } catch (\Throwable $th) {
                return "--";
            }
            
        });

        $grid->column('PiceTime', __('<a href="#">單位工時▼</a>'))->display(function($Records){
            try {
                $batch_id = $this->bid;
                $pice = Batches::where('id', $batch_id)->first()->quantity;
                $run_second = Batches::where('id', $batch_id)->first()->run_second;

                $startrecords = BatchStateRecord::where('batch_id', $batch_id)
                                                ->where('state', 'starthold')->get();
                $eedrecords = BatchStateRecord::where('batch_id', $batch_id)
                                                ->where('state', 'endhold')->get();
                $restSec = 0;
                for ($i=0; $i < sizeof($startrecords); $i++) { 
                    $start = $startrecords[$i]->created_at;
                    $end = $eedrecords[$i]->created_at;
                    $restSec += $start->diffInSeconds($end);
                }

                return round(($pice/($run_second - $restSec)),2)  .'秒';
            } catch (\Throwable $th) {
                return "--";
            }
            
        });

        $grid->column('ProdProcessesList.process_time', __('<a href="#">標準工時▼</a>'))->display(function($process_time){
            return $process_time*$this->qty.'秒';
        });

        $grid->column('DiffTime', __('<a href="#">超前工時▼</a>'))->display(function($Records){
            try {
                $batch_id = $this->bid;
                $ppId = Batches::where('id', $batch_id)->first()->prod_processes_list_id;
                $process_time = ProdProcessesList::where('id', $ppId)->first()->process_time;
                $pice = Batches::where('id', $batch_id)->first()->quantity;
                $run_second = Batches::where('id', $batch_id)->first()->run_second;

                $startrecords = BatchStateRecord::where('batch_id', $batch_id)
                                                ->where('state', 'starthold')->get();
                $eedrecords = BatchStateRecord::where('batch_id', $batch_id)
                                                ->where('state', 'endhold')->get();
                $restSec = 0;
                for ($i=0; $i < sizeof($startrecords); $i++) { 
                    $start = $startrecords[$i]->created_at;
                    $end = $eedrecords[$i]->created_at;
                    $restSec += $start->diffInSeconds($end);
                }

                return -round((($pice/($run_second - $restSec))*$this->qty - $process_time*$this->qty),2)  .'秒';
            } catch (\Throwable $th) {
                return "--";
            }
            
        });

        $grid->column('area', __('<a href="#">負責區域/部門▼</a>'))->display(function($area){
            
            dd($area);
            
            if ($area == NULL) {
                return '--';
            }
            else
            {
                return $area;
            }
            
        });
        $grid->column('state', __('<a href="#">狀態▼</a>'))->display(function($state){
            $stateArr = [
                'pending'  => '確認中', 
                'approve'   => '等待加工',
                'disapprove'=> '取消加工',
                'process'   => '加工中',
                'complete'  => '已完成',
                'starthold' => '開始暫停',
                'endhold'   => '結束暫停(執行中)',
                'cancel'    => '取消',
            ];
            return '<span class="badge badge-warning" style="background:blue">'.$stateArr[$state].'</span>';
            // return $stateArr[$state];
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

        $_departments = Department::all();
        $_departmentsMap = array();
        foreach($_departments as $item)
        {
            $_departmentsMap[$item->id] = $item->name;
        }

        $form->text('batch_code', __('批號'))->readonly();
        $form->text('run_id', __('工單'))->readonly();
        $form->select('prod_processes_list_id', __('製程與產品'))->options($_prodProcessesListMap)->readonly();
        $form->select('doer_id', __('員工'))->options($_userMap);
        
        $form->select('area', __('負責區域/部門'))->options($_departmentsMap);
        
        // $form->select('area', __('負責區域/部門'))->options([
        //     'PHOTO' => 'PHOTO',
        //     'CVD' => 'CVD'
        // ]);

        $form->number('quantity', __('數量'))->default(1);
        $form->number('scrap', __('報廢'))->default(0);
        $form->datetime('start_time', __('開始時間'))->default(date('Y-m-d H:i:s'));
        $form->datetime('end_time', __('結束時間'))->default(date('Y-m-d H:i:s'));
        $form->number('run_second', __('執行秒數'));
        $form->text('state', __('狀態'))->default('peddning')->readonly();

        return $form;
    }
}

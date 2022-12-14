<?php

namespace App\Admin\Controllers;

use App\Orders;
use App\Runs;
use App\Customers;
use App\Products;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Admin;

use App\Admin\Extensions\OrdersExporter;

class OrdersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Orders';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Orders());

        $grid->exporter(new OrdersExporter());

        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('Customer.company_name', '廠商');
            $filter->where(function ($query) {
                $query->where('order_code', 'like', "%{$this->input}%");
            }, '訂單號');
        });
        
        $grid->column('id', __('<a href="#">訂單▼</a>'))->expand(function ($model) {
            $runs = $model->Runs()->get()->map(function ($run) {
                $product = Products::where('id', $run->product_id)->first();
                $run['product_id'] = $product->product_name;
                return $run->only(['product_id', 'quantity', 'each_quantity', 'start_time', 'end_time']);
            });
                        
            return new Table(['商品','數量', '每箱數量','開始時間', '結束時間'], $runs->toArray());
        });
        $grid->column('order_code', __('<a href="#">訂單號▼</a>'));
        $grid->column('customer_id', __('<a href="#">廠商▼</a>'))->display(function($customer_id){
            $customer = Customers::where('id', $customer_id)->first();
            return $customer->company_name;
        });
        $grid->column('status', __('<a href="#">狀態▼</a>'));
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
        $show = new Show(Orders::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('order_code', __('Order code'));
        $show->field('customer_id', __('Customer id'));
        $show->field('run_id', __('Run id'));
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
        $form = new Form(new Orders());

        $_customers = Customers::all();
        $_customerMap = array();
        foreach($_customers as $item)
        {
            $_customerMap[$item->id] = $item->company_name;
        }

        $_runs = Runs::all();
        $_runMap = array();
        foreach($_runs as $item)
        {
            $_runMap[$item->id] = $item->run_code;
        }

        $form->text('order_code', __('訂單號'))->default(uniqid());
        $form->select('customer_id', __('客戶'))->options($_customerMap);

        $form->select('status', __('狀態'))->options([
            'pending' => '尚未付款',
            'compelete' => '完成付款'
        ])->default('pending');

        return $form;
    }
}

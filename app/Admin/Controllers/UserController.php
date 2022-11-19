<?php

namespace App\Admin\Controllers;

use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Admin;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        // $grid->column('id', __('Id'));
        $grid->column('line_id', __('<a href="#">Line Id▼</a>'))->display(function($value){
            if ($value != NULL) {
                return $value;
            }
            else
            {
                return '--';
            }
        });
        $grid->column('employee_id', __('<a href="#">工號▼</a>'))->display(function($value){
            if ($value != NULL) {
                return $value;
            }
            else
            {
                return '--';
            }
        });
        $grid->column('email', __('<a href="#">Email▼</a>'))->display(function($value){
            if ($value != NULL) {
                return $value;
            }
            else
            {
                return '--';
            }
        });
        $grid->column('name', __('<a href="#">姓名▼</a>'));
        $grid->column('department', __('<a href="#">部門▼</a>'))->display(function($value){
            if ($value != NULL) {
                return $value;
            }
            else
            {
                return '--';
            }
        });
        $grid->column('job_title', __('<a href="#">職稱▼</a>'));
        $grid->column('gender', __('<a href="#">姓名▼</a>'))->display(function($value){
            if ($value != NULL) {
                return $value;
            }
            else
            {
                return '--';
            }
        });
        $grid->column('state', __('<a href="#">狀態▼</a>'))->display(function($state){
            return $state==1 ? '通過':'未通過';
        });
        $grid->column('note', __('<a href="#">備註▼</a>'))->display(function($value){
            if ($value != NULL) {
                return $value;
            }
            else
            {
                return '--';
            }
        });
        $grid->column('created_at', __('<a href="#">建立時間▼</a>'));

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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('employee_id', __('Employee id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('department', __('Department'));
        $show->field('job_title', __('Job title'));
        $show->field('gender', __('Gender'));
        $show->field('state', __('State'));
        $show->field('note', __('Note'));
        $show->field('password', __('Password'));
        $show->field('remember_token', __('Remember token'));
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
        $form = new Form(new User());

        $form->text('employee_id', __('Employee id'));
        $form->text('name', __('Name'));
        $form->email('email', __('Email'));
        $form->text('department', __('Department'));
        $form->text('job_title', __('Job title'));
        $form->text('gender', __('Gender'));
        $form->switch('state', __('State'));
        $form->textarea('note', __('Note'));
        $form->password('password', __('Password'));

        $form->saving(function (Form $form) {
            if ($form->password == null)
            {
                $form->password = $form->model()->password;
            }
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            }
        });

        return $form;
    }
}

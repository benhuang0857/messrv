<?php

namespace App\Admin\Controllers;

use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

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
        $grid->column('line_id', __('Line Id'))->display(function($value){
            if ($value != NULL) {
                return $value;
            }
            else
            {
                return '--';
            }
        })->sortable();
        $grid->column('employee_id', __('工號'))->display(function($value){
            if ($value != NULL) {
                return $value;
            }
            else
            {
                return '--';
            }
        })->sortable();
        $grid->column('email', __('Email'))->display(function($value){
            if ($value != NULL) {
                return $value;
            }
            else
            {
                return '--';
            }
        })->sortable();
        $grid->column('name', __('姓名'))->sortable();
        $grid->column('department', __('部門'))->display(function($value){
            if ($value != NULL) {
                return $value;
            }
            else
            {
                return '--';
            }
        })->sortable();
        $grid->column('job_title', __('職稱'))->sortable();
        $grid->column('gender', __('姓名'))->display(function($value){
            if ($value != NULL) {
                return $value;
            }
            else
            {
                return '--';
            }
        })->sortable();
        $grid->column('state', __('狀態'))->display(function($state){
            return $state==1 ? '通過':'未通過';
        })->sortable();
        $grid->column('note', __('備註'))->display(function($value){
            if ($value != NULL) {
                return $value;
            }
            else
            {
                return '--';
            }
        })->sortable();
        $grid->column('created_at', __('建立時間'))->sortable();

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

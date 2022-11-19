<?php

namespace App\Admin\Controllers;

use App\Staffs;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class StaffsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '員工(Staffs)';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Staffs());

        // $grid->column('id', __('Id'));
        $grid->column('employee_id', __('工號'))->sortable();
        $grid->column('name', __('姓名'))->sortable();
        $grid->column('department', __('部門'))->sortable();
        $grid->column('job_title', __('職稱'))->sortable();
        $grid->column('gender', __('姓名'))->sortable();
        $grid->column('state', __('狀態'))->display(function($state){
            return $state==1 ? '通過':'未通過';
        })->sortable();
        $grid->column('note', __('備註'))->sortable();
        $grid->column('created_at', __('建立時間'))->sortable();
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
        $show = new Show(Staffs::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('employee_id', __('Employee id'));
        $show->field('name', __('Name'));
        $show->field('department', __('Department'));
        $show->field('job_title', __('Job title'));
        $show->field('gender', __('Gender'));
        $show->field('state', __('State'));
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
        $form = new Form(new Staffs());

        $form->text('employee_id', __('Employee id'));
        $form->text('name', __('Name'));
        $form->text('department', __('Department'));
        $form->text('job_title', __('Job title'));
        $form->radio('gender', __('Gender'))->options([
            'male' => '男',
            'female' => '女',
        ])->default('male');
        $form->switch('state', __('State'));
        $form->textarea('note', __('Note'));

        return $form;
    }
}

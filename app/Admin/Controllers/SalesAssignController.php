<?php

namespace App\Admin\Controllers;

use App\SalesAssign;
use App\SalesAssignDetails;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Table;

class SalesAssignController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.sales_assign'));
            $content->description(trans('admin::lang.list'));

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('admin::lang.sales_assign'));
            $content->description(trans('admin::lang.edit'));

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.sales_assign'));
            $content->description(trans('admin::lang.create'));

            $content->body($this->form());
            $content->row(new Box('title', 'xxxx'));
            $content->body($this->detail_grid());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(SalesAssign::class, function (Grid $grid) {

            $grid->id('序')->sortable();
            $grid->assign_date(trans('admin::lang.assign_date'))->sortable();
            $grid->assign_id(trans('admin::lang.assign_id'))->sortable();
            $grid->assign_total(trans('admin::lang.assign_total'));

            //$grid->created_at(trans('admin::lang.created_at'));
            //$grid->updated_at(trans('admin::lang.updated_at'));
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SalesAssign::class, function (Form $form) {

            //$form->display('id', 'ID');

            $form->datetime('assign_date',trans('admin::lang.assign_date'))->format('YYYY-MM-DD HH:mm:ss')->default('text...');
            $form->text('assign_id',trans('admin::lang.assign_id'));
            $form->display('update_user',trans('admin::lang.update_user'));

            $form->display('created_at',trans('admin::lang.created_at'));
            $form->display('updated_at',trans('admin::lang.updated_at'));
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function detail_grid()
    {
        return Admin::grid(SalesAssignDetails::class, function (Grid $grid) {

            $grid->said('序')->sortable();
            
            $grid->assign_date(trans('admin::lang.assign_date'))->sortable();
            $grid->assign_id(trans('admin::lang.assign_id'))->sortable();
            $grid->assign_total(trans('admin::lang.assign_total'));
            
            //$grid->created_at(trans('admin::lang.created_at'));
            //$grid->updated_at(trans('admin::lang.updated_at'));
        });
    }


}

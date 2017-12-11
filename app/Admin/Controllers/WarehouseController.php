<?php

namespace App\Admin\Controllers;

use App\Warehouse;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class WarehouseController extends Controller
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

            $content->header(trans('admin::lang.warehouse'));
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

            $content->header(trans('admin::lang.warehouse'));
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

            $content->header(trans('admin::lang.warehouse'));
            $content->description(trans('admin::lang.create'));

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Warehouse::class, function (Grid $grid) {

            $grid->wid('ID')->sortable();
            $grid->w_name(trans('admin::lang.name'));
            $grid->w_city(trans('admin::lang.city'));
            $grid->w_area(trans('admin::lang.area'));

            $grid->updated_at(trans('admin::lang.updated_at'));
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Warehouse::class, function (Form $form) {

            $form->display('wid', 'ID');
            $form->text('w_name', trans('admin::lang.name'))->rules('required');
            $form->text('w_phone', trans('admin::lang.phone'));
            $form->text('w_postcode', trans('admin::lang.postcode'));
            $form->text('w_city', trans('admin::lang.city'));
            $form->text('w_area', trans('admin::lang.area'));
            $form->text('w_street', trans('admin::lang.street'));
            $form->textarea('w_notes', trans('admin::lang.notes'))->rows(5);

            $form->display('created_at', trans('admin::lang.created_at'));
            $form->display('updated_at', trans('admin::lang.updated_at'));
        });
    }
}

<?php

namespace App\Admin\Controllers;

use App\WebArea;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class WebAreaController extends Controller
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

            $content->header(trans('admin::lang.web_location'));            
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

            $content->header(trans('admin::lang.web_area'));
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

            $content->header(trans('admin::lang.web_area'));
            $content->description(trans('admin::lang.new'));

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
        return Admin::grid(WebArea::class, function (Grid $grid) {

            $grid->id(trans('admin::lang.area_id'))->sortable();
            $grid->area_name(trans('admin::lang.store_name'));
            $grid->column('area_sort',trans('admin::lang.order'))->editable()->sortable();
            //$grid->area_sort(trans('admin::lang.order'))->sortable();
            $grid->created_at(trans('admin::lang.created_at'));
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
        return Admin::form(WebArea::class, function (Form $form) {

            $form->display('id', trans('admin::lang.area_id'));
            $form->text('area_name', trans('admin::lang.store_area'));
            $form->text('area_sort', trans('admin::lang.order'));
          
            $form->display('created_at',trans('admin::lang.created_at'));
            $form->display('updated_at',trans('admin::lang.updated_at'));
        });
    }
}

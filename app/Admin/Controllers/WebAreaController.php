<?php

namespace App\Admin\Controllers;

use App\WebArea;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Tree;
use Encore\Admin\Auth\Permission;

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
        Permission::check(['WebArea-Reader','WebArea-Editor','WebArea-Creator','WebArea-Deleter']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.web_area'));
            $content->description(trans('admin::lang.list'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.web_area')]
            );
            $content->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());
            });  
            // $content->body($this->grid());
        });
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        return WebArea::tree(function (Tree $tree) {
            $tree->disableCreate();
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
        Permission::check(['WebArea-Reader','WebArea-Editor','WebArea-Deleter']);
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('admin::lang.web_area'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.web_area'), 'url' => '/web/area'],
                ['text' => trans('admin::lang.edit')]
            );

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
        Permission::check(['WebArea-Reader','WebArea-Creator','WebArea-Deleter']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.web_area'));
            $content->description(trans('admin::lang.new'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.web_area'), 'url' => '/web/area'],
                ['text' => trans('admin::lang.new')]
            );

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

            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
            });
            $grid->disableFilter();
            $grid->disableExport();
            $grid->perPages([10, 20]);

            $grid->id(trans('ID'))->sortable();
            $grid->area_name(trans('admin::lang.name'));
            //$grid->column('area_sort',trans('admin::lang.order'))->editable()->sortable();
            //$grid->area_sort(trans('admin::lang.order'))->sortable();
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
        return Admin::form(WebArea::class, function (Form $form) {

            $form->display('id', trans('admin::lang.area_id'));
            $form->text('area_name', trans('admin::lang.name'));
            $form->text('area_sort', trans('admin::lang.order'));
            $form->display('created_at',trans('admin::lang.created_at'));
            $form->display('updated_at',trans('admin::lang.updated_at'));
        });
    }
}

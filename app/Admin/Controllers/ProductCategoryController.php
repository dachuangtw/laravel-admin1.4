<?php

namespace App\Admin\Controllers;

use App\ProductCategory;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Tree;

class ProductCategoryController extends Controller
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

            $content->header(trans('admin::lang.product_category'));
            $content->description(trans('admin::lang.list'));
            
            $content->body(ProductCategory::tree());

            // $content->body($this->grid());
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

            $content->header(trans('admin::lang.product_category'));
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

            $content->header(trans('admin::lang.product_category'));
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
        return Admin::grid(ProductCategory::class, function (Grid $grid) {

            $grid->pcid('ID')->sortable();
            $grid->pc_name(trans('admin::lang.name'));

            $grid->updated_at(trans('admin::lang.updated_at'));
            $grid->created_at(trans('admin::lang.created_at'));
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(ProductCategory::class, function (Form $form) {

            $form->display('pcid', 'ID');
            $form->text('pc_name', trans('admin::lang.name'))->rules('required');

            $form->display('updated_at', trans('admin::lang.updated_at'));
            $form->display('created_at', trans('admin::lang.created_at'));
        });
    }
}

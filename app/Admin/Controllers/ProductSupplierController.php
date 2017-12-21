<?php

namespace App\Admin\Controllers;

use App\ProductSupplier;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Box;

class ProductSupplierController extends Controller
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

            $content->header(trans('admin::lang.product_supplier'));
            $content->description(trans('admin::lang.list'));

            $content->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('supplier'));

                    $form->text('sup_number', trans('admin::lang.sup_number'))->rules('required');
                    $form->text('sup_name', trans('admin::lang.name'))->rules('required');
                    $form->text('sup_alias', trans('admin::lang.alias'));
                    $form->textarea('sup_notes', trans('admin::lang.notes'))->rows(3);
                    $form->hidden('update_user')->default(Admin::user()->id);

                    $column->append((new Box(trans('admin::lang.new'), $form))->style('danger'));
                });
            });
        });
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        return ProductSupplier::tree(function (Tree $tree) {
            $tree->disableCreate();

            $tree->branch(function ($branch) {
                $payload = "&nbsp;<strong>{$branch['sup_number']} - {$branch['sup_name']}</strong>";
                return $payload;
            });
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

            $content->header(trans('admin::lang.product_supplier'));
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

            $content->header(trans('admin::lang.product_supplier'));
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
        return Admin::grid(ProductSupplier::class, function (Grid $grid) {

            $grid->sup_number(trans('admin::lang.sup_number'))->sortable();
            $grid->sup_name(trans('admin::lang.name'));

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
        return Admin::form(ProductSupplier::class, function (Form $form) {

            $form->text('sup_number', trans('admin::lang.sup_number'))->rules('required');
            $form->text('sup_name', trans('admin::lang.name'))->rules('required');
            $form->text('sup_alias', trans('admin::lang.alias'));
            $form->textarea('sup_notes', trans('admin::lang.notes'))->rows(3);
            $form->hidden('update_user')->default(Admin::user()->id);
                        
            $form->display('updated_at', trans('admin::lang.updated_at'));
        });
    }
}

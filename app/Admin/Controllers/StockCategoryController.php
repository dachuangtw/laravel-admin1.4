<?php

namespace App\Admin\Controllers;

use App\StockCategory;

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
use Encore\Admin\Auth\Permission;

class StockCategoryController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        Permission::check(['StockCategory-Reader']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.stock_category'));
            $content->description(trans('admin::lang.list'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.stock_category')]
            );
            $content->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('stock/category'));

                    $form->text('sc_number', trans('admin::lang.sc_number'))->rules('required');
                    $form->text('sc_name', trans('admin::lang.name'))->rules('required');
                    $form->text('sc_notes', trans('admin::lang.notes'));

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
        return StockCategory::tree(function (Tree $tree) {
            $tree->disableCreate();
            $tree->disableView();

            $tree->branch(function ($branch) {
                $payload = "&nbsp;<strong>{$branch['sc_number']} - {$branch['sc_name']}</strong>";
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
        Permission::check(['StockCategory-Editor']);
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('admin::lang.stock_category'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.stock_category'), 'url' => '/category'],
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
        Permission::check(['StockCategory-Creator']);
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

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
        return Admin::grid(StockCategory::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(StockCategory::class, function (Form $form) {

            $form->text('sc_number', trans('admin::lang.sup_number'))->rules('required');
            $form->text('sc_name', trans('admin::lang.name'))->rules('required');
            $form->text('sc_notes', trans('admin::lang.notes'));

            $form->display('updated_at', trans('admin::lang.updated_at'));
        });
    }
}

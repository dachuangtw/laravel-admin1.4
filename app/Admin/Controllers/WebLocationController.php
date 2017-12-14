<?php

namespace App\Admin\Controllers;

use App\WebLocation;
use App\WebArea;

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

class WebLocationController extends Controller
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

            $content->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());
            });
            //$content->body($this->grid());
            $content->body($this->formarea());
        });
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        return WebLocation::tree(function (Tree $tree) {
            //$tree->disableCreate();

            $tree->branch(function ($branch) {
                $payload = "&nbsp;<strong>{$branch['store_name']}</strong>";
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

            $content->header(trans('admin::lang.web_location'));
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

            $content->header(trans('admin::lang.web_location'));
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
        return Admin::grid(WebLocation::class, function (Grid $grid) {

            $grid->id(trans('admin::lang.store_id'))->sortable();
            $grid->store_area(trans('admin::lang.store_area'))->sortable();
            $grid->store_name(trans('admin::lang.store_name'));
            $grid->created_at(trans('admin::lang.created_at'));
            $grid->updated_at(trans('admin::lang.updated_at'));
            $states = [
                'on'  => ['value' => 1, 'text' => 'ON', 'color' => 'success'],
                'off' => ['value' => 2, 'text' => 'OFF', 'color' => 'danger'],
            ];
            $grid->column('showfront',trans('admin::lang.showfront'))->status()->switch($states);
        });

    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(WebLocation::class, function (Form $form) {

            $form->display('id', trans('admin::lang.store_id'));
            $form->text('store_name', trans('admin::lang.store_name'));
            $form->select('store_area', trans('admin::lang.store_area'))->options(
                WebArea::all()->pluck('area_name','area_sort')
                );
            $form->text('store_address', trans('admin::lang.store_address'));
            $form->editor('map',trans('admin::lang.store_map'));
            $form->html('<a href="https://goo.gl/13yFtr">嵌入地圖說明</a>');

            //$form->map($latitude, $longitude,'GPS'); //經度,緯度
            $form->image('store_pic', trans('admin::lang.store_pic'))->move('/location','store_pic');
            $states = [
                'on'  => ['value' => 1, 'text' => 'ON', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => 'OFF', 'color' => 'danger'],
            ];
            $form->switch('showfront',trans('admin::lang.showfront'))->states($states);
            $form->textarea('comment',trans('admin::lang.comment'))->rows(10);
            $form->display('created_at',trans('admin::lang.created_at'));
            $form->display('updated_at',trans('admin::lang.updated_at'));
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form_area
     */
    protected function formarea()
    {
        return Admin::form(WebArea::class, function (Form $form) {
            $form->action(admin_url('web/location'));
            $form->display('id', trans('admin::lang.area_id'));
            $form->text('area_name', trans('admin::lang.name'));
            $form->text('area_sort', trans('admin::lang.order'));
          
            $form->display('created_at',trans('admin::lang.created_at'));
            $form->display('updated_at',trans('admin::lang.updated_at'));
        });
    }
}

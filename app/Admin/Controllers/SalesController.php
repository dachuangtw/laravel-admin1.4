<?php

namespace App\Admin\Controllers;

use App\Sales;
use App\Warehouse;
use App\WebLocation;

use App\Admin\Extensions\Tools\SalesResign;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
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

            $content->header(trans('admin::lang.sales'));
            $content->description(trans('admin::lang.list'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales')]
            );

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

            $content->header(trans('admin::lang.sales'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales'), 'url' => '/sales'],
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
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.sales'));
            $content->description(trans('admin::lang.new'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales'), 'url' => '/sales'],
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
        return Admin::grid(Sales::class, function (Grid $grid) {

            //接收查詢在職or離職
            if (in_array(Request::get('resign'), ['f', 't'])) {
                $grid->model()->where('resign', Request::get('resign'));
            };
            $grid->tools(function ($tools) {
                $tools->append(new SalesResign());
            });
            $grid->model()->orderBy('sales_id', 'desc');
            $grid->disableImport();//關閉匯入按鈕
            //查詢過濾器
            $grid->filter(function($filter){
                // 如果过滤器太多，可以使用弹出模态框来显示过滤器.
                $filter->useModal();
                // 禁用id查询框
                $filter->disableIdFilter();
                // sql: ... WHERE `user.name` LIKE "%$name%";
                $filter->like('sales_name', trans('admin::lang.salesname'));
                $filter->like('sales_id', trans('admin::lang.sales_id'));
            });

            $grid->sales_id(trans('admin::lang.sales_id'))->sortable();
            $grid->area_id(trans('admin::lang.location_area'))->sortable()->display(function($wid) {
                   return Warehouse::find($wid)->w_name;
               });
            $grid->name(trans('admin::lang.salesname'));
            $grid->collect_at(trans('admin::lang.collect_at'));
            $grid->created_at( trans('admin::lang.created_at'));
            $grid->updated_at( trans('admin::lang.updated_at'));
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Sales::class, function (Form $form) {
            $form->model()->makeVisible('password');

            $form->tab('基本資料', function (Form $form) {

                $form->display('sales_id', trans('admin::lang.sales_id'));
                $form->text('account','帳號')->rules('required');
                $form->password('password', trans('admin::lang.password'))->rules('required|confirmed')->default(function ($form) {
                    return $form->model()->password;
                });

                $form->password('password_confirmation', trans('admin::lang.password_confirmation'))->rules('required')
                ->default(function ($form) {
                    return $form->model()->password;
                });
                $form->ignore(['password_confirmation']);

                $form->divide();
                $form->text('name', trans('admin::lang.salesname'))->rules('required');
                $form->text('nickname', trans('admin::lang.nickname'));
                $form->select('area_id', trans('admin::lang.location_area'))
                ->options(Warehouse::all()->pluck('w_name','wid'));
                // $form->multipleSelect('store_location',trans('admin::lang.web_location'))
                // ->options(WebLocation::all()->pluck('store_name', 'id'));

                $form->divide();
                $form->radio('resign', trans('admin::lang.resign'))->options(['t' => '是','f' => '否'])->default('f');
                $form->dateRange('start_work_date', 'end_work_date',  trans('admin::lang.start_end_work_date'));

            })->tab('聯絡資訊', function (Form $form) {

                $form->mobile('cellphone',trans('admin::lang.cellphone'))->options(['mask' => '9999 999 999']);;
                $form->textarea('remarks',trans('admin::lang.notes'));

            })->tab('帳號資訊', function (Form $form) {

                $form->display('client_ip',trans('admin::lang.client_ip'));
                $form->display('client_agent',trans('admin::lang.client_agent'));
                $form->display('logged_in_at',trans('admin::lang.logged_in_at'));
                $form->display('collect_at',trans('admin::lang.collect_at'));

                $form->divide();
                $form->display('created_at', trans('admin::lang.created_at'));
                $form->display('updated_at', trans('admin::lang.updated_at'));

            });

            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
            });


        });
    }
}

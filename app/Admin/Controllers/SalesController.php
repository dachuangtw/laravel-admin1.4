<?php

namespace App\Admin\Controllers;

use App\Sales;
use App\Warehouse;
use App\WebLocation;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

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

            $content->header(trans('admin::lang.list'));
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

            $content->header(trans('admin::lang.list'));
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
        return Admin::grid(Sales::class, function (Grid $grid) {
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

            $grid->sid(trans('ID'))->sortable();
            $grid->wid(trans('admin::lang.wid'))->sortable();
            $grid->sales_id(trans('admin::lang.sales_id'))->sortable();
            $grid->sales_name(trans('admin::lang.salesname'));
            $grid->resign(trans('admin::lang.resign'))->display(function ($released) {
                return $released ? '是' : '否';
                });
            $grid->collect_at(trans('admin::lang.collect_at'));
            //$grid->created_at( trans('admin::lang.created_at'));
            //$grid->updated_at( trans('admin::lang.updated_at'));
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
                $form->display('sid', 'ID');
                $form->text('sales_id', trans('admin::lang.sales_id'))->rules('required');
                $form->select('wid', trans('admin::lang.warehouse'))->options(
                Warehouse::all()->pluck('w_name','wid')
                );
                
                $form->email('email', 'Email')->rules('required');
                $form->password('password', trans('admin::lang.password'))->rules('required|confirmed')->default(function ($form) {
                    return $form->model()->password;
                });
                $form->password('password_confirmation', trans('admin::lang.password_confirmation'))->rules('required')
                ->default(function ($form) {
                    return $form->model()->password;});
                $form->ignore(['password_confirmation']
                );
                $form->text('sales_name', trans('admin::lang.salesname'))->rules('required');
                $form->text('nickname', trans('admin::lang.nickname'))->rules('required');
                $form->radio('resign', trans('admin::lang.resign'))->options(['1' => '是','0' => '否']);

                $form->display('created_at', trans('admin::lang.created_at'));
                $form->display('updated_at', trans('admin::lang.updated_at'));

                $form->display('client_ip',trans('admin::lang.client_ip'));
                $form->display('client_agent',trans('admin::lang.client_agent'));
                $form->display('logged_in_at',trans('admin::lang.logged_in_at'));        
                $form->display('collect_at',trans('admin::lang.collect_at'));    
            })->tab('聯絡資訊', function (Form $form) {

                $form->mobile('cellphone',trans('admin::lang.cellphone'));

                $form->multipleSelect('store_location',trans('admin::lang.web_location'))->options(WebLocation::all()->pluck('store_name', 'id'));

                $form->textarea('remarks',trans('admin::lang.notes'));
            });
            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
            });

        });
    }
}

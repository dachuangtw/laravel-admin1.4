<?php

namespace App\Admin\Controllers;

use App\SalesRefund;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class SalesRefundController extends Controller
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

            $content->header(trans('admin::lang.sales_refund'));
            $content->description(trans('admin::lang.list'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_refund'), 'url' => '/sales/refund']
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

            $content->header(trans('admin::lang.sales_refund'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_refund'), 'url' => '/sales/refund'],
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

            $content->header(trans('admin::lang.sales_refund'));
            $content->description(trans('admin::lang.create'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_refund'), 'url' => '/sales/refund'],
                ['text' => trans('admin::lang.create')]
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
        return Admin::grid(SalesRefund::class, function (Grid $grid) {

            $grid->model()->orderBy('refund_id', 'desc'); // 預設排序
            $grid->actions(function ($actions) {

                $actions->setTitleExtra('領貨單號：'); // 自訂，標題前面提示
                $actions->setTitleField(['refund_id']);
                // 没有權限角色不顯示按鈕
                if (!Admin::user()->can('SalesCollect-Deleter')) {
                    $actions->disableDelete();
                }
                if (!Admin::user()->can('SalesCollect-Editor')) {
                    $actions->disableEdit();
                }
            });
            $grid->filter(function($filter){
                $filter->disableIdFilter();
            });
            $grid->number('No.');
            $grid->rows(function ($row, $number) {
                $row->column('number', $number+1);
            });
            $grid->refund_date(trans('admin::lang.refund_date'))->sortable();
            $grid->refund_id(trans('admin::lang.refund_id'))->sortable();
            //判斷是否為超級管理員，則只可看所屬倉庫內容
            if(!Admin::user()->isAdministrator()){
                $grid->model()->where('wid',Admin::user()->wid);    
            }else{
                $grid->wid(trans('admin::lang.warehouse'))->sortable()->display(function($wid) {
                    return Warehouse::find($wid)->w_name;
                })->label('info');
            }
            $grid->sales_id(trans('admin::lang.salesname').'/'.trans('admin::lang.nickname'))->display(function($sales_id) {
                return Sales::find($sales_id)->name.' <font size="1"  color="blue">('.Sales::find($sales_id)->nickname.')';
            });
            $grid->refund_amount(trans('admin::lang.refund_amount'))->sortable();








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
        return Admin::form(SalesRefund::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}

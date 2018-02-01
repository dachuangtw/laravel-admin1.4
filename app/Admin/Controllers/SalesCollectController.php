<?php

namespace App\Admin\Controllers;

use App\SalesCollect;
use App\Sales;
use App\Warehouse;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class SalesCollectController extends Controller
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

            $content->header(trans('admin::lang.sales_collect'));
            $content->description(trans('admin::lang.list'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_collect')]
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

            $content->header(trans('admin::lang.sales_collect'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_collect'), 'url' => '/sales/collect'],
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

            $content->header(trans('admin::lang.sales_collect'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_collect'), 'url' => '/sales/collect'],
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
        return Admin::grid(SalesCollect::class, function (Grid $grid) {

            $grid->model()->orderBy('collect_date', 'desc'); // 預設排序
            $grid->filter(function($filter){
                $filter->disableIdFilter();
                if(Admin::user()->isAdministrator()){
                    $filter->where(function ($query) {
                        $query->where('wid',  "{$this->input}");
                    }, trans('admin::lang.warehouse'))->select(
                        Warehouse::all()->pluck('w_name', 'wid')->toArray()
                    );
                }  
                $filter->like('collect_id',trans('admin::lang.collect_id'));
                $filter->between('collect_date', trans('admin::lang.collect_date'))->date();
            });
            $grid->number('No.')->sortable();
            $grid->rows(function ($row, $number) {
                $row->column('number', $number+1);
            });
            $grid->collect_date(trans('admin::lang.collect_date'))->sortable();
            $grid->collect_id(trans('admin::lang.collect_id'))->sortable();

            //判斷是否為超級管理員，則只可看所屬倉庫內容
            if(!Admin::user()->isAdministrator()){
                $grid->model()->where('wid',Admin::user()->wid);    
            }else{
                $grid->wid(trans('admin::lang.warehouse'))->sortable()->display(function($wid) {
                    return Warehouse::find($wid)->w_name;
                })->label('info');
            }
            $grid->sales_id(trans('admin::lang.salesname'))->display(function($sales_id) {
                    return Sales::find($sales_id)->name;
                });
            $grid->collect_amount(trans('admin::lang.collect_amount'))->sortable();
            $grid->update_user(trans('admin::lang.update_user'))->display(function($userId) {
                   return Administrator::find($userId)->name;
            });

            // $grid->created_at();
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
        return Admin::form(SalesCollect::class, function (Form $form) {

            // $form->display('id', 'ID');
            if(strpos(url()->current(), '/edit') !== false) {
                $form->date('collect_date',trans('admin::lang.collect_date'))->readOnly();
                $form->text('collect_id',trans('admin::lang.collect_id'))->readOnly();
                if(Admin::user()->isAdministrator()){
                    $form->select('wid',trans('admin::lang.wid'))
                        ->options(Warehouse::all()->pluck('w_name','wid'))->readOnly();
                }else{
                    $form->hidden('wid',trans('admin::lang.wid'))->value(Admin::user()->wid);
                    $form->select('sales_id','業務'.trans('admin::lang.salesname'))
                    ->options(Sales::all()->where('wid',Admin::user()->wid)->pluck('name','sales_id'))->readOnly(); 
                }         
            }else{
                $form->date('collect_date',trans('admin::lang.collect_date'))->defaultdate('YYYY-MM-DD');
                // $form->html('<font color="#333">系統自動產生</font>',trans('admin::lang.collect_id'));
                //判斷超級使用者
                if(Admin::user()->isAdministrator()){
                    $form->select('wid',trans('admin::lang.wid'))
                        ->options(Warehouse::all()->pluck('w_name','wid'));
                    // $form->select('sales_id','業務'.trans('admin::lang.salesname'))
                    // ->options(Sales::all()->where('wid',Admin::user()->wid)->pluck('name','sales_id'))
                }else{
                    $form->hidden('wid',trans('admin::lang.wid'))->value(Admin::user()->wid);
                    
                } 
                $form->select('sales_id','業務'.trans('admin::lang.salesname'))
                    ->options(Sales::all()->where('wid',Admin::user()->wid)->pluck('name','sales_id'));      
            }

            $form->hidden('collect_id',trans('admin::lang.collect_id'));
            $form->textarea('collect_notes',trans('admin::lang.notes'))->rows(2);
            $form->hidden('update_user')->value(Admin::user()->id);
            $form->hidden('collect_amount');
            $form->divide();
            $form->button('btn-danger btn-append','+ 配貨商品')->on('click','ShowModal("salescollect_hasstock");');

            // $form->display('created_at', 'Created At');
            // $form->display('updated_at', 'Updated At');

             $form->saving(function (Form $form) {
                



             });
        });
    }
}

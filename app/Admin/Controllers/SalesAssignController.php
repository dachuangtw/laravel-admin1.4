<?php

namespace App\Admin\Controllers;

use App\SalesAssign;
use App\SalesAssignDetails;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Request;
use App\Admin\Extensions\Tools\DateChooser;

class SalesAssignController extends Controller
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

            $content->header(trans('admin::lang.sales_assign'));
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

            $content->header(trans('admin::lang.sales_assign'));
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

            $content->header(trans('admin::lang.sales_assign'));
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
        return Admin::grid(SalesAssign::class, function (Grid $grid) {
            $grid->disableImport();//關閉匯入按鈕

            $grid->tools(function ($tools) {
                $tools->append(new DateChooser());
            });

            if (in_array(Request::get('assign_date'), [date('Ymd'),date('Ymd')-1])) {
                $grid->model()->where('assign_date', Request::get('assign_date'));
                // $grid->model()->where('assign_date',date('Ymd'));
            }
            
            // $grid->said('序')->sortable();
            $grid->assign_date(trans('admin::lang.assign_date'))->sortable();
            $grid->assign_id(trans('admin::lang.assign_id'))->sortable();
            $grid->assign_total(trans('admin::lang.assign_total'))->sortable();
            $grid->update_user(trans('admin::lang.update_user'))->display(function($userId) {
                   return Admin::user($userId)->name;
            });

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
        return Admin::form(SalesAssign::class, function (Form $form) {

            //$form->display('id', 'ID');
            // $form->setView('admin::product2');
            $form->date('assign_date',trans('admin::lang.assign_date'))->defaultdate('YYYY-MM-DD');
            //判斷是否為編輯狀態
            $segment = Request::segment(5);//請求片段(5)        
            if ($segment == 'edit') {
                $form->display('assign_id',trans('admin::lang.assign_id'));
            } else {
                $form->text('assign_id',trans('admin::lang.assign_id'))->value(date('Ymd'))->rules('required');
            }
            $form->textarea('assign_notes',trans('admin::lang.notes'));
            $form->hidden('update_user')->value(Admin::user()->id);
            $form->display('created_at',trans('admin::lang.created_at'));
            $form->display('updated_at',trans('admin::lang.updated_at'));
            $form->divide();
            
            $form->hasMany('salesassigndetails','商品清單', function (Form\NestedForm $form) {          
                $form->text('pid');
                $form->text('s_type');
                $form->text('p_salesprice');
                $form->text('p_quantity');
                $form->text('p_salesprice_total');
                $form->hidden('created_at');
            }); 
        });       
    }
}

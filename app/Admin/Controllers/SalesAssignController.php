<?php

namespace App\Admin\Controllers;

use Encore\Admin\Auth\Database\Administrator;
use App\SalesAssign;
use App\SalesAssignDetails;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Auth\Permission;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Widgets\Alert;
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
            

            $content->row(function (Row $row) {
                $row->column(5, function (Column $column){
                    $count = SalesAssign::where('assign_id', date('Ymd'))->count();
                    if ($count > 0){        
                        $column->append((new Alert('今日已配貨!!'))->style('warning')->icon('cubes'));
                    }else {
                        // $column->append((new Alert('今日未配貨!!'))->style('info')->icon('cubes'));
                    }
                });
            });

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
        Permission::check(['editor']);
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
        Permission::check(['creator']);
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
        Permission::check(['reader']);
        return Admin::grid(SalesAssign::class, function (Grid $grid) {
            
            $grid->model()->orderBy('assign_date', 'desc'); // 預設排序
            $grid->filter(function($filter){
                $filter->disableIdFilter();
                $filter->like('assign_id',trans('admin::lang.assign_id'));
                // $filter->between('assign_date', trans('admin::lang.assign_date'))->date();
            });

            $grid->tools(function ($tools) {
                $tools->append(new DateChooser());
            });
             
            switch (Request::get('assign_date'))
            {
                case 'lastmonth':
                    $lastmonth = array(date('Y-m-01', strtotime('-1 month')),date('Y-m-t', strtotime('-1 month')));
                    $grid->model()->whereBetween('assign_date', $lastmonth);
                    break;
                case 'thismonth':
                    $thismonth = array(date('Y-m-01'),date('Ymt'));
                    $grid->model()->whereBetween('assign_date', $thismonth);
                    break;
                // default:
                //     echo Request::get('assign_date');
            }

            $grid->assign_date(trans('admin::lang.assign_date'))->sortable();
            $grid->assign_id(trans('admin::lang.assign_id'))->sortable();
            $grid->assign_total(trans('admin::lang.assign_total'))->sortable();
            $grid->update_user(trans('admin::lang.update_user'))->display(function($userId) {
                   return Administrator::find($userId)->name;
            });

            //$grid->created_at(trans('admin::lang.created_at'));
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
        return Admin::form(SalesAssign::class, function (Form $form) {

            //$form->display('id', 'ID');
            // $form->setView('admin::product2');
            //判斷是否為編輯狀態
            $segment = Request::segment(5);//請求片段(5)        
            if ($segment == 'edit') {
                $form->display('assign_date',trans('admin::lang.assign_date'));
                $form->display('assign_id',trans('admin::lang.assign_id'));
            } else {
                $form->date('assign_date',trans('admin::lang.assign_date'))->defaultdate('YYYY-MM-DD');
                $form->text('assign_id',trans('admin::lang.assign_id'))->value(date('Ymd'))->rules('required');
                // $form->hidden('assign_id');
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
            $form->saving(function (Form $form) {
                if (!empty(request()->assign_id)){
                    $form->assign_id = str_replace('-','',dump($form->assign_date));
                }
                if($form->assign_date !== $form->model()->assign_date && SalesAssign::where('assign_date',$form->assign_date)->value('said')){
                    $error = new MessageBag(['title'=>'提示','message'=>'此配貨單已存在!']);
                    return back()->withInput()->with(compact('error'));
                }
            });
        });       
    }
}

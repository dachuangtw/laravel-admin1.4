<?php

namespace App\Admin\Controllers;

use Encore\Admin\Auth\Database\Administrator;
use App\SalesNotes;
use App\Sales;
use App\Warehouse;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Auth\Permission;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Table;

class SalesNoteController extends Controller
{
    use ModelForm;
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        Permission::check(['reader']);   
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.sales_note'));
            $content->description(trans('admin::lang.list'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_note')]
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
        Permission::check(['editor']);
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('admin::lang.sales_note'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_note'), 'url' => '/sales/note'],
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
        Permission::check(['creator']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.sales_note'));
            $content->description(trans('admin::lang.new'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_note'), 'url' => '/sales/note'],
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
        return Admin::grid(SalesNotes::class, function (Grid $grid) {

            $grid->disableImport();//關閉匯入按鈕
            $grid->filter(function($filter){
                // 禁用id查詢框
                $filter->disableIdFilter();
                // sql: ... WHERE `user.created_at` BETWEEN $start AND $end;
                $filter->between('note_at', trans('admin::lang.note_at'))->datetime();
            });
            $grid->disableExport();
            $grid->model()->orderBy('id', 'desc');
            $grid->number('No.')->sortable();
            $grid->rows(function ($row, $number) {
                $row->column('number', $number+1);
            });
            $grid->note_at(trans('admin::lang.note_at'))->sortable();
            $grid->note_title(trans('admin::lang.title'));
            //超級管理員可看到所有業務資料，其他為各倉庫
            if(Admin::user()->isAdministrator()){
                $grid->note_wid(trans('admin::lang.location_area'));
                // ->display(function($wid) {
                //    return Warehouse::find($wid)->w_name;
                // });
                
                // ->label('info');
            }else{
                $grid->model()->where('note_wid', 'like', '%'.Admin::user()->wid.'%');
                // $grid->model()->where('note_wid', 'like', '%'.'-1'.'%');
            }

            $grid->column('詳情')->expand(function (){
                $header =[trans('admin::lang.note_content')];
                $note_content = $this->note_content;
                $rows = [[$note_content]];
                return new Table($header,$rows);
            });

            $grid->created_at(trans('admin::lang.created_at'));
            $grid->updated_at(trans('admin::lang.updated_at'));
            $grid->update_user(trans('admin::lang.update_user'))->display(function($userId) {
                   return Administrator::find($userId)->name;
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SalesNotes::class, function (Form $form) {

            //$form->display('id','ID');

            $form->text('note_title', trans('admin::lang.title'))->rules('required');
            $form->datetime('note_at', trans('admin::lang.note_at'))->default(date("Y-m-d G:i:s"));
            
            if(Admin::user()->isAdministrator()){
                //超級管理員可對所有倉庫及業務發公告
                $form->multipleSelect('note_wid',trans('admin::lang.warehouse'))->options(
                    ['-1'=> '全部倉庫'] + Warehouse::all()->pluck('w_name', 'wid')->toArray()
                );
                // $form->html('<input id="wid-checkBox" type="checkbox"> 全選');
                $form->multipleSelect('note_target',trans('admin::lang.note_target'))->options(
                    ['-1' => '全部業務'] + Sales::all()->pluck('name', 'sales_id')->toArray()
                );
                // $form->html('<input id="target-checkBox" type="checkbox"> 全選');
            }else{
                //各倉庫發業務公告
                $form->multipleSelect('note_wid',trans('admin::lang.note_target'))
                ->options(Warehouse::all()->pluck('w_name', 'wid'))->default([Admin::user()->wid])->readonly();
            }
            $form->editor('note_content', trans('admin::lang.note_content'));
            $form->hidden('update_user')->default(Admin::user()->id);
            $form->display('created_at',trans('admin::lang.created_at'));
            $form->display('updated_at',trans('admin::lang.updated_at'));
            $form->saving(function (Form $form) {
                $form->update_user = Admin::user()->id;
                if (!Admin::user()->isAdministrator()){
                    $form->note_wid = [Admin::user()->wid];
                }
            });
        });
    }
}

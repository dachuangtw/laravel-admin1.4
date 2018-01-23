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
use Encore\Admin\Layout\Row;
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
            //關閉眼睛功能
            $grid->actions(function ($actions) {
                $actions->disableView();
            }); 

            $grid->filter(function($filter){
                $filter->disableIdFilter();// 禁用id查詢框
                if(Admin::user()->isAdministrator()){
                    $filter->where(function ($query) {
                    $query->where('note_wid',  "{$this->input}")
                        ->orWhere('note_wid', 'like', "%{$this->input}|")
                        ->orWhere('note_wid', 'like', "{$this->input}|%")
                        ->orWhere('note_wid', 'like', "%|{$this->input}|%");
                    }, trans('admin::lang.warehouse'))->select(
                        ['-1'=> '全部倉庫'] + Warehouse::all()->pluck('w_name', 'wid')->toArray()
                    );
                }                
                $filter->between('note_at', trans('admin::lang.note_at'))->date();
            });

            $grid->model()->orderBy('id', 'desc');
            $grid->number('No.')->sortable();
            $grid->rows(function ($row, $number) {
                $row->column('number', $number+1);
            });
            
            $grid->note_at(trans('admin::lang.note_at'))->sortable();
            $grid->note_title(trans('admin::lang.title'));

            //超級管理員可看到所有業務資料，其他為各倉庫
            //可以看到系統管理員給各倉庫業務公告但不能修改刪除動作
            if(Admin::user()->isAdministrator()){
              
            }else{
                $grid->model()->where(function ($query) {
                    $query->where('note_wid',  Admin::user()->wid)
                        ->orWhere('note_wid', 'like', '%'.Admin::user()->wid.'|')
                        ->orWhere('note_wid', 'like', Admin::user()->wid.'|%')
                        ->orWhere('note_wid', 'like', '%|'.Admin::user()->wid.'|%');
                        // ->orWhere('note_wid', '-1');
                });           
            }
            
            $grid->note_wid(trans('admin::lang.warehouse'))//->pluck()
                ->display(function($wid){
                    $note_wid = implode('|',$wid);                       
                    if ($note_wid == '-1'){
                        return '全部倉庫';
                    }else{
                        $note_wid2 = explode('|',$note_wid);
                        foreach ($note_wid2 as $value) {
                            $w_name[] = Warehouse::find($value)->w_name;
                        }
                        return $w_name;
                    }
            })->label('info');
                

            $grid->column('公告詳情')->expand(function (){
                $header = [trans('admin::lang.note_content')];
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

            $form->text('note_title', trans('admin::lang.title'))->rules('required');
            $form->datetime('note_at', trans('admin::lang.note_at'))->default(date("Y-m-d G:i:s"));
            
            if(Admin::user()->isAdministrator()){
                //超級管理員可對所有倉庫及業務發公告
                $form->multipleSelect('note_wid',trans('admin::lang.warehouse'))->options(
                    ['-1'=> '全部倉庫'] + Warehouse::all()->pluck('w_name', 'wid')->toArray()
                )->rules('required');
                $form->multipleSelect('note_target',trans('admin::lang.note_target'))->options(
                    ['-1' => '全部業務'] + Sales::all()->pluck('name', 'sales_id')->toArray()
                )->rules('required');
            }else{
                //各倉庫發業務公告
                $form->multipleSelect('note_wid',trans('admin::lang.note_target'))
                ->options(Warehouse::all()->pluck('w_name', 'wid'))->default([Admin::user()->wid])->readonly();
                // $form->hidden('note_wid')->default(Admin::user()->wid);
                $form->multipleSelect('note_target',trans('admin::lang.note_target'))->options(
                    ['-1' => '全部業務'] + Sales::all()->where('area_id',Admin::user()->wid)
                    ->pluck('name', 'sales_id')->toArray()
                )->rules('required');
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

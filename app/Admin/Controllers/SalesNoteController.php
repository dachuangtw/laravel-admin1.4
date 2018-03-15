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
use Illuminate\Support\MessageBag;

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
        Permission::check(['SalesNotes-reader']);
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
        Permission::check(['SalesNotes-editor']);
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('admin::lang.sales_note'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_note'), 'url' => '/sales/notes'],
                ['text' => trans('admin::lang.edit')]
            );
            //比對是否為update_user，否則無權訪問編輯及刪除公告，超級管理員權限all(暫定)
            $check_update_user = SalesNotes::all()->where('id',$id)->pluck('update_user');
            if($check_update_user [0] ==  Admin::user()->id || Admin::user()->isAdministrator()){
                $content->body($this->form()->edit($id));
            }else{
                $error = new MessageBag([
                    'title'  => trans('admin::lang.deny'),
                ]);
                return back()->with(compact('error'));
            }
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        Permission::check(['SalesNotes-creator']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.sales_note'));
            $content->description(trans('admin::lang.new'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_note'), 'url' => '/sales/notes'],
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
                // 没有`deleter權限角色不顯示刪除按鈕
                if (!Admin::user()->can('SalesNotes-deleter')) {
                    $actions->disableDelete();
                }
                //判斷是否為更新者可編輯及刪除
                if ($actions->row->update_user != Admin::user()->id) {
                    $actions->disableDelete();
                    $actions->disableEdit();
                }
            });
            $grid->disableExport();//關閉匯出按鈕
            $grid->filter(function($filter){
                $filter->disableIdFilter();// 禁用id查詢框
                if(Admin::user()->isAdministrator()){
                    $filter->where(function ($query) {
                    $query->where('note_wid',  "{$this->input}")
                        ->orWhere('note_wid', 'like', "%{$this->input}|")
                        ->orWhere('note_wid', 'like', "{$this->input}|%")
                        ->orWhere('note_wid', 'like', "%|{$this->input}|%");
                    }, trans('admin::lang.warehouse'))->select(
                        ['-1'=> '全部倉庫'] + Warehouse::orderBy('sort')->pluck('name', 'id')->toArray()
                    );
                }
                $filter->between('note_at', trans('admin::lang.note_at'))->date();
            });

            $grid->model()->orderBy('id', 'desc');
            $grid->number('No.');
            $grid->rows(function ($row, $number) {
                $row->column('number', $number+1);
            });

            $grid->note_at(trans('admin::lang.note_at'))->sortable();
            $grid->note_title(trans('admin::lang.title'));

            //各倉庫可以看到系統管理員給各倉庫業務公告
            // if(Admin::user()->isAdministrator()){
            // }else{
            if(!Admin::user()->isAdministrator()){
                $grid->model()->where(function ($query) {
                    $query->where('note_wid',  Admin::user()->wid)
                        ->orWhere('note_wid', 'like', '%'.Admin::user()->wid.'|')
                        ->orWhere('note_wid', 'like', Admin::user()->wid.'|%')
                        ->orWhere('note_wid', 'like', '%|'.Admin::user()->wid.'|%')
                        ->orWhere('note_wid', '-1');
                });
                $grid->disableRowSelector();
            }

            $grid->note_wid(trans('admin::lang.warehouse'))
                ->display(function($wid){
                    $note_wid = implode('|',$wid);
                    if ($note_wid == '-1'){
                        return '全部倉庫';
                    }else{
                        $note_wid2 = explode('|',$note_wid);
                        foreach ($note_wid2 as $value) {
                            $w_name[] = Warehouse::find($value)->name;
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
                    ['-1'=> '全部倉庫'] + Warehouse::orderBy('sort')->pluck('name', 'id')->toArray()
                )->rules('required');

                $form->multipleSelect('note_target',trans('admin::lang.note_target'))->options(
                    ['-1' => '全部業務']+ Sales::all()->pluck('name', 'id')->toArray()
                )->rules('required');
            }else{
                //各倉庫發業務公告
                $form->multipleSelect('note_wid',trans('admin::lang.warehouse'))->options(
                    Warehouse::orderBy('sort')->where('id',Admin::user()->wid)->pluck('name', 'id'))
                    ->default([Admin::user()->wid])->rules('required');
                $form->multipleSelect('note_target',trans('admin::lang.note_target'))->options(
                    ['-1' => '全部業務'] + Sales::all()->where('warehouse_id', Admin::user()->wid)
                    ->pluck('name', 'id')->toArray()
                )->default('-1')->rules('required');
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

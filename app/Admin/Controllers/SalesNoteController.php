<?php

namespace App\Admin\Controllers;

use Encore\Admin\Auth\Database\Administrator;
use App\SalesNotes;
use App\Sales;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

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
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.sales_note'));
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

            $content->header(trans('admin::lang.sales_note'));
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

            $content->header(trans('admin::lang.sales_note'));
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
        return Admin::grid(SalesNotes::class, function (Grid $grid) {

            $grid->disableImport();//關閉匯入按鈕
            $grid->filter(function($filter){
                // 禁用id查詢框
                $filter->disableIdFilter();
                // sql: ... WHERE `user.created_at` BETWEEN $start AND $end;
                $filter->between('note_at', trans('admin::lang.note_at'))->datetime();
            });
            $grid->disableExport();

            $grid->id('ID')->sortable();
            $grid->note_at(trans('admin::lang.note_at'))->sortable();
            $grid->note_title(trans('admin::lang.title'));
            //$grid->note_content(trans('admin::lang.note_content'));
            //$grid->note_target(trans('admin::lang.note_target'));
           //$grid->created_at(trans('admin::lang.created_at'));
            //$grid->updated_at(trans('admin::lang.updated_at'));
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
            $form->multipleSelect('note_target',trans('admin::lang.note_target'))->options(Sales::all()->pluck('name', 'sales_id'))->help('預設為全部');
            $form->editor('note_content', trans('admin::lang.note_content'));
            $form->hidden('update_user')->value(Admin::user()->id);
            $form->display('created_at',trans('admin::lang.created_at'));
            $form->display('updated_at',trans('admin::lang.updated_at'));
        });
    }
}

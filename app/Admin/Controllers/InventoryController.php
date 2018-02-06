<?php

namespace App\Admin\Controllers;

use App\Inventory;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class InventoryController extends Controller
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

            $content->header(trans('admin::lang.inventory'));
            $content->description(trans('admin::lang.index'));

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

            $content->header(trans('admin::lang.inventory'));
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

            $content->header(trans('admin::lang.inventory'));
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
        return Admin::grid(Inventory::class, function (Grid $grid) {

            // $grid->inid('ID')->sortable();
            $grid->in_number(trans('admin::lang.in_number'))->sortable();
            $grid->wid(trans('admin::lang.warehouse'))->sortable()->display(function($wid) {
                return Warehouse::find($wid)->w_name;
            })->sortable();
            $grid->update_user(trans('admin::lang.update_user'))->display(function ($update_user) {                
                return Administrator::find($update_user)->name;
            })->sortable();
            $grid->srart_at(trans('admin::lang.srart_at'));
            $grid->finish_at(trans('admin::lang.finish_at'));

            $grid->actions(function ($actions) {
                $actions->setTitleExtra('盤點單號：'); // 自訂，標題前面提示
                $actions->setTitleField(['in_number']);

                // 没有權限不顯示按鈕
                if (Admin::user()->cannot('Inventory-Editor')) {
                    $actions->disableEdit();
                }
                if (Admin::user()->cannot('Inventory-Deleter')) {
                    $actions->disableDelete();
                }
            });

            //依角色有不同的篩選
            if(Admin::user()->isRole('Warehouse')){
                $grid->model()->where(function ($query) {
                    $query->where('wid',Admin::user()->wid);
                })->orderBy('inid', 'desc');
            }elseif(!Admin::user()->isRole('Superwarehouse')){
                //非倉管人員
                $grid->model()->where(function ($query) {
                    $query->where('wid',  Admin::user()->wid)
                    ->where('srart_at','<', date('Y-m-d H:i:s'))
                    ->where('finish_at','>', date('Y-m-d H:i:s'));
                })->orderBy('inid', 'desc');
                $grid->disableCreateButton();
            }
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Inventory::class, function (Form $form) {
            $form->datetimeRange('srart_at', 'finish_at',  trans('admin::lang.inventory_range'));
            $form->hidden('wid')->default(Admin::user()->wid);
            $form->hidden('update_user')->default(Admin::user()->id);
            $form->hidden('in_number');
            $form->saving(function(Form $form) {
                if (empty(request()->srart_at) || empty(request()->finish_at)) {
                    $error = new MessageBag(['title'=>'提示','message'=>'未正確填寫盤點時間!']);
                    return back()->withInput()->with(compact('error'));
                }
                /**
                 * 盤點單編碼規則：民國年YYY(3)月MM(2)+倉庫編號XX(2)+流水號(1)，共8碼
                 */
                if (empty(request()->in_number)) {
                    $Todaydate = (date('Y') - 1911) . date('md');
                    //前補0至兩碼                 
                    $wid = str_pad(request()->wid,2,"0",STR_PAD_LEFT);

                    //取得該日該倉庫盤點單號的最大值
                    $max_number = Inventory::withTrashed()->where('in_number', 'like', $Todaydate.$wid.'%')
                    ->max('in_number');
                    
                    if (!empty($max_number)) {
                        //取後1碼做+1計算
                        $lastCode = (int)mb_substr($max_number, -1, 1, "utf-8");
                        $lastCode++;
                    } else {
                        $lastCode = 1;
                    }

                    //填充到in_number欄位中
                    $form->in_number = $Todaydate.$wid.$lastCode;
                }
            });
        });
    }
}

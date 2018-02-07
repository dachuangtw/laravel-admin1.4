<?php

namespace App\Admin\Controllers;

use App\Inventory;
use App\InventoryDetails;
use App\Stock;
use App\Warehouse;
use Encore\Admin\Auth\Database\Administrator;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
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
     * Create interface.
     *
     * @return Content
     */
    public function counting()
    {
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.inventory'));
            $content->description(trans('admin::lang.counting'));

            
            $content->row(function (Row $row) {
                /**
                 * 功能：搜尋商品，
                 * 可見欄位：商品名
                 */
                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('product/searchstock'));
                    $form->method('GET');

                    /**
                     * !important Bug：搜尋功能和filter密不可分...
                     * 這裡要什麼欄位，filter就必須有那個欄位，才能正常搜尋
                     */                    
                    $form->text('search', trans('admin::lang.p_name'));

                    $form->disableSubmit();
                    $form->disableReset();
                    $form->enableSearch();

                    $column->append((new Box(trans('admin::lang.search'), $form))->style('success'));
                });

            });
            $content->body($this->grid());
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
            $grid->start_at(trans('admin::lang.start_at'));
            $grid->finish_at(trans('admin::lang.finish_at'));
            $grid->update_user(trans('admin::lang.update_user'))->display(function ($update_user) {                
                return Administrator::find($update_user)->name;
            })->sortable();

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
                $actions->ensableInventory();
            });

            //依角色有不同的篩選
            if(Admin::user()->isRole('Warehouse')){
                $grid->model()->where(function ($query) {
                    $query->where('wid',Admin::user()->wid);
                });
            }elseif(!Admin::user()->isRole('Superwarehouse') && !Admin::user()->isAdministrator()){
                //非倉管人員
                $grid->model()->where(function ($query) {
                    $query->where('wid',  Admin::user()->wid)
                    ->where('start_at','<', date('Y-m-d H:i:s'))
                    ->where('finish_at','>', date('Y-m-d H:i:s'));
                });
                $grid->disableCreation();
            }
            $grid->model()->orderBy('inid', 'desc');
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
            $form->datetimeRange('start_at', 'finish_at',  trans('admin::lang.inventory_range'));
            $form->hidden('wid')->default(Admin::user()->wid);
            $form->hidden('update_user')->default(Admin::user()->id);
            $form->hidden('in_number');
            $form->saving(function(Form $form) {
                if (empty(request()->start_at) || empty(request()->finish_at)) {
                    $error = new MessageBag(['title'=>'提示','message'=>'未正確填寫盤點時間!']);
                    return back()->withInput()->with(compact('error'));
                }
                /**
                 * 盤點單編碼規則：民國年YYY(3)月MM(2)+倉庫編號XX(2)+流水號(1)，共8碼
                 */
                if (empty(request()->in_number)) {
                    $Todaydate = (date('Y') - 1911) . date('m');
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


                    /**
                     * 填充資料到inventory_details資料表
                     */
                    $stocks = Stock::where('wid', Admin::user()->wid)->where('st_stock','>',0)->get()->sortByDesc('pid');

                    $insertInventoryDetailsArray = [];
                    foreach($stocks as $stock){
                        $insertInventoryDetailsArray[] = [
                            'in_number' => $form->in_number,
                            'pid' => $stock->pid,
                            'stid' => $stock->stid,
                            'ind_type' => $stock->st_type,
                            'ind_stock' => $stock->st_stock,
                            'update_user' => Admin::user()->id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                    }
                    InventoryDetails::insert($insertInventoryDetailsArray);
                }
            });
        });
    }
}

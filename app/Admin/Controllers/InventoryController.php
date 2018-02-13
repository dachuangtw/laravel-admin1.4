<?php

namespace App\Admin\Controllers;

use App\Inventory;
use App\InventoryDetails;
use App\Stock;
use App\StockLog;
use App\Warehouse;
use App\ProductIndex;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Permission;

use Encore\Admin\Widgets\Table;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Request;

class InventoryController extends Controller
{
    use ModelForm;

    /**
     * 盤點確認 並 修改庫存 / 新增庫存變更紀錄
     */
    public function checked()
    {
        $inid = Request::get('inid');
        $checked = Request::get('checked');
        $Inventory = Inventory::where('inid',$inid)->select('in_number','wid')->first();
        $in_number = $Inventory->in_number;
        $wid = $Inventory->wid;

        // $in_number = Inventory::find($inid)->in_number;
        $count = InventoryDetails::where('in_number',$in_number)->where('ind_at',NULL)->count();
        if($count > 0){
            if ($checked != 'checked') {
                return 'check';                    
            }else{
                //盤點數自動填充
                $NoInventory = InventoryDetails::where('in_number', $in_number)->where('ind_at',NULL)->get();
                foreach ($NoInventory as $eachone) {
                    InventoryDetails::find($eachone->indid)->update([
                        'ind_quantity'  =>  $eachone->ind_stock,
                    ]);
                }
            }
        }
        //盤點庫存變更
        $insertStockLogArray = [];
        $InventoryDetails = InventoryDetails::where('in_number', $in_number)->where('ind_at','!=',NULL)->get();
        foreach($InventoryDetails as $eachone){
            $ind_difference = (int)$eachone->ind_quantity - (int)$eachone->ind_stock;
            InventoryDetails::find($eachone->indid)->update([
                'ind_difference'  =>  $ind_difference,
            ]);
            Stock::find($eachone->stid)->update([
                'st_stock'  =>  $eachone->ind_quantity
            ]);
            if ($ind_difference != 0) {
                $insertStockLogArray[] = [
                    'pid'           =>  $eachone->pid,
                    'wid'           =>  $wid,
                    'stid'          =>  $eachone->stid,
                    'sl_calc'       =>  ($ind_difference > 0) ? '+' :'-',
                    'sl_quantity'   =>  abs($ind_difference),
                    'sl_stock'      =>  $eachone->ind_quantity,
                    'sl_notes'      =>  "盤點損益：".$in_number,
                    'update_user'   =>  Admin::user()->id,
                    'updated_at'    =>  date('Y-m-d H:i:s'),
                ];
            }
        }
        StockLog::insert($insertStockLogArray);
        Inventory::find($inid)->update(['in_checked'=>'1']);
        return 'ok';
    }

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
            $content->breadcrumb(
                ['text' => trans('admin::lang.inventory')]
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

            $content->header(trans('admin::lang.inventory'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.inventory'), 'url' => 'inventory'],
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

            $content->header(trans('admin::lang.inventory'));
            $content->description(trans('admin::lang.new'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.inventory'), 'url' => 'inventory'],
                ['text' => trans('admin::lang.new')]
            );

            $content->body($this->form());
        });
    }

    /**
     * View interface.
     *
     * @param $id
     * @return Content
     */
    public function view($inid)
    {
        
        Permission::check(['Inventory-Reader']);

        $Inventory = Inventory::where('inid',$inid)->select('in_number','wid')->first();
        $InventoryDetails = InventoryDetails::where('in_number',$Inventory->in_number)->get()->sortByDesc('pid');
        $w_name = Warehouse::find($Inventory->wid)->w_name;
        
        $header = ['商品名(款式)','庫存數','盤點數','差異數','備註','盤點人'];
        $rows = [];

        foreach($InventoryDetails as $detail){
            if($detail->ind_type == '不分款'){
                $detail->ind_type = '';
            }else{
                $detail->ind_type = ' (' .$detail->ind_type. ')';
            }
            $ProductIndex = ProductIndex::where('pid',$detail->pid)->select('p_name','p_number','p_pic')->first();
            $detail->p_name = $ProductIndex->p_name . $detail->ind_type;
            $detail->p_number = $ProductIndex->p_number;
            $detail->p_pic = $ProductIndex->p_pic;
            if($detail->p_pic){
                $detail->p_name = '<a href="#" role="button" data-toggle="popover" data-container="#viewmodal" data-placement="bottom" data-html="true" data-content="<img src=\''.config('admin.upload.host').'/'.$detail->p_pic.'\' width=\'150px\'>" >' .$detail->p_name. '</a>';
            }


            if ($detail->ind_user) {
                $detail->ind_user = Administrator::find($detail->ind_user)->name;
            }
            if((int)$detail->ind_difference < 0){
                $detail->ind_difference = '<strong style="color:#dd4b39">' .$detail->ind_difference. '</strong>';
            }
            $rows[] = [
                $detail->p_name,
                $detail->ind_stock,
                $detail->ind_quantity,
                $detail->ind_difference,
                $detail->ind_notes,
                $detail->ind_user,
            ];
        }
        $table = new Table($header, $rows);
        $table->class('table table-hover text-center');
        return $table->render();
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
            $grid->wid(trans('admin::lang.warehouse'))->display(function($wid) {
                return Warehouse::find($wid)->w_name;
            })->sortable();
            $grid->start_at(trans('admin::lang.start_at'));
            $grid->finish_at(trans('admin::lang.finish_at'));
            $grid->update_user(trans('admin::lang.update_user'))->display(function ($update_user) {                
                return Administrator::find($update_user)->name;
            })->sortable();

            $grid->in_checked(trans('admin::lang.in_checked'))->display(function ($in_checked) {
                return $in_checked ? "已確認" : "<button data-inid='". $this->inid. "' class='btn btn-success btn-sm in_checked'>確認</button>";
            });

            $token = csrf_token();
            $script = <<<SCRIPT
            var checked = null, checkedbtn = null;
            var checkfun = function(){
                if(checked === null){
                    checkedbtn = $(this);
                    checkedbtn.hide().parent().append('<span>庫存調整中...</span>');
                }
                $.ajax({
                    url:'inventory/checked',
                    method: 'post',
                    data: {
                        inid: checkedbtn.attr("data-inid"),
                        checked: checked,
                        _token: "$token"
                    },
                    success: function (response) {
                        if(response == 'check' && checked === null){
                            if (confirm("尚有未盤點商品，確認盤點完成？未盤點商品庫存將不變")) {
                                checked = 'checked';
                                checkfun();
                                checked = null;
                            }else{
                                checkedbtn.show().parent().find("span").remove();
                            }
                        }else if(response == 'ok'){
                            checkedbtn.parent().text('已確認');
                        }else{
                            alert(response);
                        }
                    },
                    error: function (jqXHR, exception) {
                        var msg = '';
                        if (jqXHR.status === 0) {
                            msg = 'Not connect.Verify Network.';
                        } else if (jqXHR.status == 404) {
                            msg = 'Requested page not found. [404]';
                        } else if (jqXHR.status == 500) {
                            msg = 'Internal Server Error [500].';
                        } else if (exception === 'parsererror') {
                            msg = 'Requested JSON parse failed.';
                        } else if (exception === 'timeout') {
                            msg = 'Time out error.';
                        } else if (exception === 'abort') {
                            msg = 'Ajax request aborted.';
                        } else {
                            msg = 'Uncaught Error.' + jqXHR.responseText;
                        }
                        alert(msg + '網頁出了問題，請通知相關人員處理');
                    },
                });
            };
            $("button.in_checked").on("click",checkfun);
SCRIPT;
         Admin::script($script);

            $grid->actions(function ($actions) { 
                $w_name = Warehouse::find($actions->row->wid)->w_name;               
                $actions->setTitleExtra(['盤點單號：', $w_name]); // 自訂，可字串可陣列，字串在TitleFirld前，陣列則是[0]+TitleField+[1]
                $actions->setTitleField(['in_number']);

                // 没有權限不顯示按鈕
                if (Admin::user()->cannot('Inventory-Editor')) {
                    $actions->disableEdit();
                }
                if (Admin::user()->cannot('Inventory-Reader')) {
                    $actions->disableView();
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
    protected function form($id = null)
    {
        return Admin::form(Inventory::class, function (Form $form) use($id){
            $form->datetimeRange('start_at', 'finish_at',  trans('admin::lang.inventory_range'));
            $form->hidden('wid')->default(Admin::user()->wid);
            $form->hidden('update_user')->default(Admin::user()->id);
            $form->hidden('in_number');
            $form->hidden('in_checked')->default(0);
            $form->saving(function(Form $form) use($id){
                if (empty($id)) {
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
                        $wid = str_pad(request()->wid, 2, "0", STR_PAD_LEFT);

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
                        $stocks = Stock::where('wid', Admin::user()->wid)->where('st_stock', '>', 0)->get()->sortByDesc('pid');

                        $insertInventoryDetailsArray = [];
                        foreach ($stocks as $stock) {
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
                }
            });
        });
    }
}

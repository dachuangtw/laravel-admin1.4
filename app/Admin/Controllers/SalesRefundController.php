<?php

namespace App\Admin\Controllers;

use Encore\Admin\Auth\Database\Administrator;
use App\SalesRefund;
use App\SalesRefundDetails;
use App\Sales;
use App\Warehouse;
use App\ProductIndex;
use App\Stock;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Illuminate\Support\MessageBag;
use Encore\Admin\Auth\Permission;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Table;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class SalesRefundController extends Controller
{
    use ModelForm;

    /**
     * 編輯時，回傳配貨單明細
     */
    public function salesrefunddetails($id)
    {
        $stock = $rowWidth = $rowLeft = $rowTitle = [];
        $action = 'editcheck';
        $detailid = 'srdid';

        $firsttime = true;
        $inputtext = true;
        $allReadonly = '';
        $SalesRefund = SalesRefund::find($id);
        $savedDetails = SalesRefundDetails::ofselected($SalesRefund->refund_id) ?: [];
        foreach($savedDetails as $key => $value){
            $products[$key] = ProductIndex::find($value->pid);
            // $stock[$value->stid] = Stock::find($value->stid)->st_type;
        }
        // $rowWidth = [33,100,33,150,60,80,80,80,80,110];
        // $rowLeft = [0,33,133,166,316,376,456,536,616,696];        
        $rowWidth = [33,180,33,150,60,80,80,80,110];
        $rowLeft = [0,33,213,246,396,456,536,616,696];
        $rowTitle = ['','商品編號','點貨','商品名','單位',/*'款式',*/'數量','單價(業務)','總價','備註'];
        $showPrice = 'srd_salesprice';
        $showQuantity = 'srd_quantity';
        $showAmount = 'srd_amount';
        $showNotes = 'srd_notes';
        $checkProduct = 'srd_check';
        $rowTop = -30;
        $rowEvenOdd = ['even','odd'];
        $data = compact('action','detailid','checkProduct','products','showPrice','showQuantity','showAmount','showNotes','rowWidth','rowLeft','rowTitle','rowTop','rowEvenOdd','firsttime','inputtext','allReadonly','savedDetails','stock');

        return view('admin::productdetails', $data);
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.sales_refund'));
            $content->description(trans('admin::lang.list'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_refund'), 'url' => '/sales/refund']
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

            $content->header(trans('admin::lang.sales_refund'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_refund'), 'url' => '/sales/refund'],
                ['text' => trans('admin::lang.edit')]
            );
            //判斷倉庫，否則無權訪問編輯，超級管理員權限all(暫定)
            $check_wid = SalesRefund::find($id)->wid;
            if($check_wid == Admin::user()->wid || Admin::user()->isAdministrator()){
                $content->body($this->form()->edit($id));
                $script = <<<SCRIPT
                ShowSalesRefundDetails('$id');
SCRIPT;
                Admin::script($script);
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
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.sales_refund'));
            $content->description(trans('admin::lang.create'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_refund'), 'url' => '/sales/refund'],
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
        return Admin::grid(SalesRefund::class, function (Grid $grid) {

            $grid->model()->orderBy('srid', 'desc'); // 預設排序
            $grid->actions(function ($actions) {

                $actions->setTitleExtra('退貨單號：'); // 自訂，標題前面提示
                $actions->setTitleField(['refund_id']);
                // 没有權限角色不顯示按鈕
                if (!Admin::user()->can('SalesCollect-Deleter')) {
                    $actions->disableDelete();
                }
                if (!Admin::user()->can('SalesCollect-Editor')) {
                    $actions->disableEdit();
                }
            });
            $grid->filter(function($filter){
                $filter->disableIdFilter();
            });
            $grid->number('No.');
            $grid->rows(function ($row, $number) {
                $row->column('number', $number+1);
            });
            $grid->refund_date(trans('admin::lang.refund_date'))->sortable();
            $grid->refund_id(trans('admin::lang.refund_id'))->sortable();
            //判斷是否為超級管理員，則只可看所屬倉庫內容
            if(!Admin::user()->isAdministrator()){
                $grid->model()->where('wid',Admin::user()->wid);
            }else{
                $grid->wid(trans('admin::lang.warehouse'))->sortable()->display(function($wid) {
                    return Warehouse::find($wid)->w_name;
                })->label('info');
            }
            $grid->sales_id(trans('admin::lang.salesname').'/'.trans('admin::lang.nickname'))->display(function($sales_id) {
                return Sales::find($sales_id)->name.' <font size="1"  color="blue">('.Sales::find($sales_id)->nickname.')';
            });
            $grid->refund_amount(trans('admin::lang.refund_amount'))->sortable();
            $grid->refund_method(trans('admin::lang.refund_method'))->value(function ($refund_method) {
                return $refund_method ? "<span class='label label-success'>退回庫存</span>" : "<span class='label label-danger'>報廢</span>";
            });
            $grid->refundgoods_check(trans('admin::lang.refundgoods_check'))->value(function ($refundgoods_check) {
                return $refundgoods_check ? "<span class='label label-success'>已退貨</span>" : "<span class='label label-danger'>未退貨</span>";
            })->sortable();
            //隱藏退款確認(此功能暫不需要)
            // $grid->refund_check(trans('admin::lang.refund_check'))->value(function ($refund_check) {
            //     return $refund_check ? "<span class='label label-success'>已退款</span>" : "<span class='label label-danger'>未退款</span>";
            // })->sortable();
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
        return Admin::form(SalesRefund::class, function (Form $form) {

            $url_edit = strpos(url()->current(), '/edit') !== false;
            //判斷為編輯狀態
            if($url_edit) {
                $form->date('refund_date',trans('admin::lang.refund_date'))->readOnly();
                $form->display('refund_id',trans('admin::lang.refund_id'))->setWidth(2, 2);
                switch (Admin::user()) {
                    case 'Administrator':
                        $form->display('wid', trans('admin::lang.wid'))->with(function ($wid) {
                            return Warehouse::find($wid)->w_name;
                        });
                        break;
                    default:
                        $form->hidden('wid',trans('admin::lang.wid'))->value(Admin::user()->wid);
                }
                $form->text('collect_id',trans('admin::lang.collect_id'))->setWidth(2, 2);
                $form->display('sales_id', '業務'.trans('admin::lang.salesname'))->setWidth(2, 2)->with(function ($sales_id) {
                    return Sales::find($sales_id)->name;
                });
            }else{
                $form->date('refund_date',trans('admin::lang.refund_date'))->rules('required')->defaultdate('YYYY-MM-DD');
                $form->html('<font color="#333">系統自動產生</font>',trans('admin::lang.refund_id'));
                $form->text('collect_id',trans('admin::lang.collect_id'))->setWidth(2, 2);
                //判斷超級使用者
                // if(Admin::user()->isAdministrator()){
                    // $form->select('wid',trans('admin::lang.wid'))
                    //     ->options(Warehouse::all()->pluck('w_name','wid'));
                // }else{
                    $form->hidden('wid',trans('admin::lang.wid'))->value(Admin::user()->wid);
                    $form->select('sales_id','業務'.trans('admin::lang.salesname'))
                    ->options(Sales::all()->where('wid',Admin::user()->wid)->pluck('name','sales_id'))->rules('required')->setWidth(2, 2)->help('必填');
                // }
            }
            $form->textarea('refund_notes', trans('admin::lang.notes'))->rows(2)->setWidth(2, 2);

            $form->divide();
            $states = [
                'on'  => ['value' => 1, 'text' => '退回庫存', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '報廢', 'color' => 'danger'],
            ];
            $form->switch('refund_method', trans('admin::lang.refund_method'))->states($states);
            $states = [
                'on'  => ['value' => 1, 'text' => '已退貨', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '未退貨', 'color' => 'danger'],
            ];
            $form->switch('refundgoods_check', trans('admin::lang.refundgoods_check'))->states($states);
            $form->select('refundgoods_check_user', trans('admin::lang.refundgoods_check_user'))
                ->options(Admin::user()->where('wid',Admin::user()->wid)->pluck('name','id'))->setWidth(2, 2);
            //隱藏退款確認(此功能暫不需要)
            // $states = [
            //     'on'  => ['value' => 1, 'text' => '已退款', 'color' => 'success'],
            //     'off' => ['value' => 0, 'text' => '未退款', 'color' => 'danger'],
            // ];
            // $form->switch('refund_check', trans('admin::lang.refund_check'))->states($states);
            // $form->select('refund_check_user', trans('admin::lang.refund_check_user'))
            //     ->options(Admin::user()->where('wid',Admin::user()->wid)->pluck('name','id'))->setWidth(2, 2);
            $form->hidden('refund_id',trans('admin::lang.refund_id'));
            $form->hidden('update_user')->value(Admin::user()->id);
            $form->hidden('refund_amount');
            $form->divide();
            $form->button('btn-warning btn-append','- 退貨商品')->on('click','ShowModal("salesrefund_hasstock");');


            $form->saving(function (Form $form) {
                if(empty(request()->pid)){
                    $error = new MessageBag(['title'=>'提示','message'=>'未填寫退貨商品!']);
                    return back()->withInput()->with(compact('error'));
                } 
                // dd(request()->refundgoods_check);
                if(request()->refundgoods_check != 'off' && empty(request()->refundgoods_check_user)){
                    $error = new MessageBag(['title'=>'提示','message'=>'未填寫退貨確認人!']);
                    return back()->withInput()->with(compact('error'));
                } 
                // 業務退貨單號編碼:所選擇退貨日期+三位數(自動增加)，共11碼 ex:第一筆 20180202001
                if (empty(request()->refund_id)){
                    //選擇業務退貨日期
                    $refund_date = str_replace('-','',dump($form->refund_date));
                    //取得業務退貨日期退貨單號的最大值
                    $max_number = SalesRefund::withTrashed()->where('refund_id', 'like', $refund_date.'%')
                    ->max('refund_id');

                    if(!empty($max_number)){
                        //取後三碼做+1計算
                        $lastThreeCode = (int)mb_substr($max_number,-3,3,"utf-8");
                        $lastThreeCode++;
                    }else{
                        $lastThreeCode = 1;
                    }
                    //前補0至三碼
                    $lastThreeCode = str_pad($lastThreeCode,3,'0',STR_PAD_LEFT);
                    $form->refund_id = $refund_date.$lastThreeCode;
                }
                $srd_amount = request()->amount;
                $srd_salesprice = request()->price;
                $srd_quantity = request()->quantity;
                $srd_notes = request()->notes;
                $stid = request()->stid;
                $srdid = request()->srdid; //退貨明細id
                $check_product = request()->checkproduct;
                $insertProductLogArray = [];
                $insertStockLogArray = [];
                $dataArray = [];
                $stidArray = [];
                $total = 0;

                /** 
                * 新增 退貨明細
                */
                if(request()->action == 'create'){
                    foreach(request()->pid as $key => $pid){
                        $dataArray[] = [
                            'pid'           =>  $pid, //商品編號
                            'refund_id'    =>  $form->refund_id, //配貨單號
                            'stid'          =>  $stid[$key], //庫存id
                            'srd_amount'    =>  $srd_amount[$key], //總金額
                            'srd_salesprice'=>  $srd_salesprice[$key], //業務單價
                            'srd_quantity'  =>  $srd_quantity[$key], //數量
                            // 'srd_check'     =>  $check_product[$key], //點貨確認
                            'srd_notes'     =>  $srd_notes[$key], //備註
                        ];
                        $stidArray[] = $stid[$key];
                        $total += $srd_amount[$key];
                    }
                    // dd($dataArray);
                    SalesRefundDetails::where('refund_id',$form->refund_id)->delete();
                    SalesRefundDetails::insert($dataArray);

                }
                /*****************************
                 * 
                 * 編輯 退貨單明細
                 * 
                 *****************************/
                elseif(request()->action == 'edit'){

                    //原本的配貨明細(數量/明細id)
                    $retailSrdid = SalesRefundDetails::where('refund_id',$form->refund_id)->pluck('srd_quantity','srdid')->toArray();
                    
                    //欲刪除的配貨明細 - 使用unset($deletesrdid[$srdid])移除沒有要刪除的配貨明細
                    $deleteSrdid = array_keys($retailSrdid);
                    
                    //配貨明細更新:新增/修改/刪除
                    foreach(request()->pid as $key => $pid){

                        $insertSalesRefubdArray[] = [
                            'pid'           =>  $pid, //商品編號
                            'refund_id'    =>  $form->refund_id, //配貨單號
                            'stid'          =>  $stid[$key], //庫存id
                            'srd_amount'    =>  $srd_amount[$key], //總金額
                            'srd_salesprice'=>  $srd_salesprice[$key], //業務單價
                            'srd_quantity'  =>  $srd_quantity[$key], //數量
                            'srd_check'     =>  $check_product[$key], //點貨確認
                            'srd_notes'     =>  $srd_notes[$key], //備註
                        ];

                        $stidArray[] = $stid[$key];//庫存id
                        $total += $srd_amount[$key];
                        if (isset($srdid[$key])) { //更新原本的
                            SalesRefundDetails::updateOrCreate(['srdid' => $deleteSrdid[$key]], $insertSalesRefubdArray[$key]);
                            $unsetKey = array_search($srdid[$key],$deleteSrdid);
                            unset($deleteSrdid[$unsetKey]); 
                        }elseif(empty($srdid[$key])){ //新增增加的
                            SalesRefundDetails::create($insertSalesRefubdArray[$key]); 
                        }
                    }
                    //刪除移除的
                    SalesRefundDetails::whereIn('srdid',$deleteSrdid)->delete();
                }
                $form->refund_amount = $total;
                $form->update_user = Admin::user()->id;
            });
        });
    }
}

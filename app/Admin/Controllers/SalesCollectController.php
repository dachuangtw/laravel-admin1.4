<?php

namespace App\Admin\Controllers;

use Encore\Admin\Auth\Database\Administrator;
use App\SalesCollect;
use App\SalesCollectDetails;
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
 /**
 * 業務領貨
 * 尚未完成:
 *      1.商品無庫存時，跳出提醒視窗。
 */
class SalesCollectController extends Controller
{
    use ModelForm;
    /**
     * 編輯時，回傳配貨單明細
     */
    public function salescollectdetails($id)
    {
        $stock = $rowWidth = $rowLeft = $rowTitle = [];
        $action = 'editcheck';
        $detailid = 'scdid';

        $firsttime = true;
        $inputtext = true;
        $allReadonly = '';
        $SalesCollect = SalesCollect::find($id);
        $savedDetails = SalesCollectDetails::ofselected($SalesCollect->collect_id) ?: [];
        foreach($savedDetails as $key => $value){
            $products[$key] = ProductIndex::find($value->pid);
            $stock[$value->stid] = Stock::find($value->stid)->st_type;
        }
        $rowWidth = [33,100,33,150,60,80,80,80,80,110];
        $rowLeft = [0,33,133,166,316,376,456,536,616,696];
        $rowTitle = ['','商品編號','點貨','商品名','單位','款式','數量','單價(業務)','總價','備註'];
        $showPrice = 'scd_salesprice';
        $showQuantity = 'scd_quantity';
        $showAmount = 'scd_amount';
        $showNotes = 'scd_notes';
        $checkProduct = 'scd_check';
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
        Permission::check(['Salescollect-Reader']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.sales_collect'));
            $content->description(trans('admin::lang.list'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_collect')]
            );

            $content->body($this->grid());
        });
    }

    /**
     * View interface.
     * 眼睛查看
     * @param $id
     * @return Content
     */
    public function view($id)
    {
        Permission::check(['Salescollect-Reader']);
        $salescollect = SalesCollect::find($id)->toArray();
        //忽略不顯示的欄位
        $skipArray = ['scid','receipt_check','receipt_check_user','created_at','updated_at','deleted_at'];
        //顯示圖片欄位
        $imgArray = [];
        $salescollect['wid'] = Warehouse::find($salescollect['wid'])->w_name;
        $salescollect['sales_id'] = Sales::find($salescollect['sales_id'])->name;
        $salescollect['collect_assign'] = $salescollect['collect_assign'] == 1  
            ? "<span class='label label-success'>已配貨</span>":"<span class='label label-danger'>未配貨</span>";
        $salescollect['collect_check'] = $salescollect['collect_check'] == 1  
            ? "<span class='label label-success'>已領貨</span>" : "<span class='label label-danger'>未領貨</span>";
        $salescollect['collect_check_user'] = !empty( $salescollect['collect_check_user']) 
            ?  Administrator::find($salescollect['collect_check_user'])->name : '';
        //隱藏收款確認(此功能暫不需要)
        // $salescollect['receipt_check'] = $salescollect['receipt_check'] == 1  
        //     ? "<span class='label label-success'>已收款</span>" : "<span class='label label-danger'>未收款</span>";
        // $salescollect['receipt_check_user'] = !empty($salescollect['receipt_check_user'])
        //     ? Administrator::find($salescollect['receipt_check_user'])->name : '';
        $salescollect['update_user'] = Administrator::find($salescollect['update_user'])->name;

        $header[] = '領貨單資訊';
        foreach($salescollect as $key => $value){            

            if(in_array($key,$skipArray) || empty($value))
                continue;   
            //欄位中文化
            $newkey = trans('admin::lang.'.$key);
            //倉庫編號/最近更新者
            //如果有換行\n改成<br>
            $rows[$newkey] = nl2br($value);            
        }
        $table = new Table($header, $rows);
        $table->class('table table-hover');
    
        $stock = $rowWidth = $rowLeft = $rowTitle = [];
        $savedDetails = SalesCollectDetails::ofselected($salescollect['collect_id']) ?: [];
        foreach($savedDetails as $key => $value){
            $products[$key] = ProductIndex::find($value->pid);
            $stock[$key] = Stock::find($value->stid)->st_type;
        }
        $action = 'view';
        $detailid = 'scdid';
        $firsttime = true;
        $inputtext = false;

        $rowWidth = [33,100,150,60,80,80,80,80,110];
        $rowLeft = [0,33,133,283,343,423,503,583,663];
        $rowTitle = ['','商品編號','商品名','單位','款式','數量','單價(業務)','總價','備註'];
        $showPrice = 'scd_salesprice';
        $showQuantity = 'scd_quantity';
        $showAmount = 'scd_amount';
        $showNotes = 'scd_notes';
        $rowTop = -30;
        $rowEvenOdd = ['even','odd'];
        
        $data = compact('action','detailid','products','showPrice','showQuantity','showAmount','showNotes','rowWidth','rowLeft','rowTitle','rowTop','rowEvenOdd','firsttime','inputtext','savedDetails','stock');
        
        return $table->render().view('admin::productdetails', $data); 
    }
    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        Permission::check(['Salescollect-Editor']);
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('admin::lang.sales_collect'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_collect'), 'url' => '/sales/collect'],
                ['text' => trans('admin::lang.edit')]
            );
            //判斷配貨倉庫，否則無權訪問編輯，超級管理員權限all(暫定)
            $check_wid = SalesCollect::find($id)->wid;
            if($check_wid == Admin::user()->wid || Admin::user()->isAdministrator()){
                $content->body($this->form()->edit($id));
                $script = <<<SCRIPT
                ShowSalesCollectDetails('$id');
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
        Permission::check(['Salescollect-Creator']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.sales_collect'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_collect'), 'url' => '/sales/collect'],
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
        Permission::check(['SalesCollect-Reader']);
        return Admin::grid(SalesCollect::class, function (Grid $grid) {

            $grid->model()->orderBy('collect_id', 'desc'); // 預設排序
            $grid->actions(function ($actions) {

                $actions->setTitleExtra('領貨單號：'); // 自訂，標題前面提示
                $actions->setTitleField(['collect_id']);
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
                // $filter->useModal();
                if(Admin::user()->isAdministrator()){
                    $filter->where(function ($query) {
                        $query->where('wid',  "{$this->input}");
                    }, trans('admin::lang.warehouse'))->select(
                        Warehouse::all()->pluck('w_name', 'wid')->toArray()
                    );
                }  
                $filter->like('collect_id',trans('admin::lang.collect_id'));                
                $filter->where(function ($query) {
                    $query->where('collect_check',  "{$this->input}");
                }, trans('admin::lang.collect_check'))->select([0 => '未領貨', 1 => '已領貨',]);
                //隱藏收款確認(此功能暫不需要)
                // $filter->where(function ($query) {
                //     $query->where('receipt_check',  "{$this->input}");
                // }, trans('admin::lang.receipt_check'))->select([0 => '未收款', 1 => '已收款',]);
                // $filter->between('collect_date', trans('admin::lang.collect_date'))->date();
                $filter->where(function ($query) {
                    $query->where(\DB::raw("date_format(collect_date, '%Y-%m-%d')"), '>=', "{$this->input}");
                }, '配貨日期(起)')->date();
                $filter->where(function ($query) {
                    $query->where(\DB::raw("date_format(collect_date, '%Y-%m-%d')"), '<=', "{$this->input}");
                }, '配貨日期(迄)')->date();
                //業務姓名查詢
                $filter->where(function ($query) {
                    $Sid_name = Sales::where('name','like',"%{$this->input}%")->pluck('sales_id');
                    $Sid_nickname = Sales::where('nickname','like',"%{$this->input}%")->pluck('sales_id');
                    // $key = NULL;
                    // dump($Sid_name);
                    foreach ($Sid_name as $key => $sid){
                        if ($key == 0 ){
                            $query->where('sales_id',$sid);
                        }else{
                            $query->orwhere('sales_id',$sid);
                        }
                    }   
                    // dump($key);
                    // if ($key == NULL)
                    $query->orwhere('sales_id',"%{$this->input}%");               
                }, trans('admin::lang.salesname'));

            });
            $grid->number('No.')->sortable();
            $grid->rows(function ($row, $number) {
                $row->column('number', $number+1);
            });
            $grid->collect_date(trans('admin::lang.collect_date'))->sortable();
            $grid->collect_id(trans('admin::lang.collect_id'))->sortable();
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
            $grid->collect_amount(trans('admin::lang.collect_amount'))->sortable();
            $grid->collect_check(trans('admin::lang.collect_check'))->value(function ($collect_check) {
                return $collect_check ? "<span class='label label-success'>已領貨</span>" : "<span class='label label-danger'>未領貨</span>";
            })->sortable();
            //隱藏收款確認(此功能暫不需要)
            // $grid->receipt_check(trans('admin::lang.receipt_check'))->value(function ($receipt_check) {
            //     return $receipt_check ? "<span class='label label-success'>已收款</span>" : "<span class='label label-danger'>未收款</span>";
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
        return Admin::form(SalesCollect::class, function (Form $form) {
            $url_edit = strpos(url()->current(), '/edit') !== false;
            //判斷為編輯狀態
            if($url_edit) {
                $form->date('collect_date',trans('admin::lang.collect_date'))->readOnly();
                $form->display('collect_id',trans('admin::lang.collect_id'))->setWidth(2, 2);
                switch (Admin::user()) {
                    case 'Administrator':
                        $form->display('wid', trans('admin::lang.wid'))->with(function ($wid) {
                            return Warehouse::find($wid)->w_name;
                        });
                        break;
                    default:
                        $form->hidden('wid',trans('admin::lang.wid'))->value(Admin::user()->wid); 
                }
                $form->display('sales_id', '業務'.trans('admin::lang.salesname'))->setWidth(2, 2)->with(function ($sales_id) {
                    return Sales::find($sales_id)->name;
                });         
            }else{
                $form->date('collect_date',trans('admin::lang.collect_date'))->defaultdate('YYYY-MM-DD');
                $form->html('<font color="#333">系統自動產生</font>',trans('admin::lang.collect_id'));
                //判斷超級使用者
                // if(Admin::user()->isAdministrator()){
                    // $form->select('wid',trans('admin::lang.wid'))
                    //     ->options(Warehouse::all()->pluck('w_name','wid'));
                // }else{
                    $form->hidden('wid',trans('admin::lang.wid'))->value(Admin::user()->wid);
                    $form->select('sales_id',trans('admin::lang.salesname'))
                    ->options(Sales::all()->where('wid',Admin::user()->wid)->pluck('name','sales_id'))->rules('required')->setWidth(2, 2);   
                // }   
            }

            $form->textarea('collect_notes', trans('admin::lang.notes'))->rows(2)->setWidth(2, 2);
            $form->divide();
            $states = [
                'on'  => ['value' => 1, 'text' => '配貨', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '未配貨', 'color' => 'danger'],
            ];
            $form->switch('collect_assign', trans('admin::lang.collect_assign'))->states($states);
            $states = [
                'on'  => ['value' => 1, 'text' => '已領貨', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '未領貨', 'color' => 'danger'],
            ];
            $form->switch('collect_check', trans('admin::lang.collect_check'))->states($states)->help('領貨確認後，庫存立即變更!');
            $form->ignore(['collect_check']);//忽略保存
            $form->select('collect_check_user', trans('admin::lang.collect_check_user'))
                ->options(Admin::user()->where('wid',Admin::user()->wid)->pluck('name','id'))->setWidth(2, 2);
            //隱藏收款確認(此功能暫不需要)    
            // $states = [
            //     'on'  => ['value' => 1, 'text' => '已收款', 'color' => 'success'],
            //     'off' => ['value' => 0, 'text' => '未收款', 'color' => 'danger'],
            // ];
            // $form->switch('receipt_check', trans('admin::lang.receipt_check'))->states($states);
            // $form->select('receipt_check_user', trans('admin::lang.receipt_check_user'))
            //     ->options(Admin::user()->where('wid',Admin::user()->wid)->pluck('name','id'))->setWidth(2, 2);
            $form->hidden('collect_id',trans('admin::lang.collect_id'));
            $form->hidden('update_user')->value(Admin::user()->id);
            $form->hidden('collect_amount');
            $form->divide();
            $form->button('btn-danger btn-append','+ 領貨商品')->on('click','ShowModal("salescollect_hasstock");');
            if($url_edit)
                $form->button('btn-warning btn-append','- 退貨商品');
            else 
                $form->button('btn-warning btn-append disabled','- 退貨');
            // $form->display('created_at', 'Created At');
            // $form->display('updated_at', 'Updated At');

            $form->saving(function (Form $form) {
                if(empty(request()->pid)){
                    $error = new MessageBag(['title'=>'提示','message'=>'未填寫領貨商品!']);
                    return back()->withInput()->with(compact('error'));
                }   
                
                // 業務領貨單號編碼:所選擇領貨日期+三位數(自動增加)，共11碼 ex:第一筆 20180202001                
                if (empty(request()->collect_id)){
                    //選擇業務領貨日期
                    $collect_date = str_replace('-','',dump($form->collect_date));
                    //取得業務領貨日期領貨單號的最大值
                    $max_number = SalesCollect::withTrashed()->where('collect_id', 'like', $collect_date.'%')
                    ->max('collect_id');
                    
                    if(!empty($max_number)){
                        //取後三碼做+1計算
                        $lastTwoCode = (int)mb_substr($max_number,-3,3,"utf-8");
                        $lastTwoCode++; 
                    }else{
                        $lastTwoCode = 1;
                    }
                    //前補0至三碼
                    $lastTwoCode = str_pad($lastTwoCode,3,'0',STR_PAD_LEFT);
                    $form->collect_id = $collect_date.$lastTwoCode;
                }
                $scd_amount = request()->amount;
                $scd_salesprice = request()->price;
                $scd_quantity = request()->quantity;
                $scd_notes = request()->notes;
                $stid = request()->stid; 
                $scdid = request()->scdid; //領貨明細id
                $check_product = request()->checkproduct;
                $insertProductLogArray = [];
                $insertStockLogArray = [];
                $dataArray = [];
                $stidArray = [];
                $total = 0;
                 /**
                 * 新增 領貨單明細
                 */
                if(request()->action == 'create'){
                    foreach(request()->pid as $key => $pid){
                        $dataArray[] = [
                            'pid'           =>  $pid, //商品編號
                            'collect_id'    =>  $form->collect_id, //配貨單號
                            'stid'          =>  $stid[$key], //庫存id
                            'scd_amount'    =>  $scd_amount[$key], //總金額
                            'scd_salesprice'=>  $scd_salesprice[$key], //業務單價
                            'scd_quantity'  =>  $scd_quantity[$key], //數量
                            'scd_check'     =>  $check_product[$key], //點貨確認
                            'scd_notes'     =>  $scd_notes[$key], //備註
                        ];
                        $stidArray[] = $stid[$key];
                        $total += $scd_amount[$key];
                    }
                    
                    SalesCollectDetails::where('collect_id',$form->collect_id)->delete();
                    SalesCollectDetails::insert($dataArray);
                }

                /*****************************
                 * 
                 * 編輯 配貨單明細
                 * 
                 *****************************/
                elseif(request()->action == 'edit'){

                    //原本的配貨明細(數量/明細id)
                    $retailScdid = SalesCollectDetails::where('collect_id',$form->collect_id)->pluck('scd_quantity','scdid')->toArray();
                    
                    //欲刪除的配貨明細 - 使用unset($deletescdid[$scdid])移除沒有要刪除的配貨明細
                    $deleteScdid = array_keys($retailScdid);
                    
                    //配貨明細更新:新增/修改/刪除
                    foreach(request()->pid as $key => $pid){

                        $insertSalesCollectArray[] = [
                            'pid'           =>  $pid,
                            'collect_id'    =>  $form->collect_id,
                            'stid'          =>  $stid[$key],
                            'scd_amount'    =>  $scd_amount[$key],
                            'scd_salesprice'=>  $scd_salesprice[$key],
                            'scd_quantity'  =>  $scd_quantity[$key],
                            'scd_check'     =>  $check_product[$key], //點貨確認
                            'scd_notes'     =>  $scd_notes[$key],
                        ];

                        $stidArray[] = $stid[$key];//庫存id
                        $total += $scd_amount[$key];
                        if (isset($scdid[$key])) { //更新原本的
                            SalesCollectDetails::updateOrCreate(['scdid' => $deleteScdid[$key]], $insertSalesCollectArray[$key]);
                            $unsetKey = array_search($scdid[$key],$deleteScdid);
                            unset($deleteScdid[$unsetKey]); 
                        }elseif(empty($scdid[$key])){ //新增增加的
                            SalesCollectDetails::create($insertSalesCollectArray[$key]); 
                        }
                    }
                    //刪除移除的
                    SalesCollectDetails::whereIn('scdid',$deleteScdid)->delete();
                }
                $form->collect_amount = $total;
                $form->update_user = Admin::user()->id;
            });
            
            $form->saved(function (Form $form) {
                /* 庫存變更 */
                //原來領貨確認
                $recheck_collect = SalesCollect::where('collect_id',$form->collect_id)->value('collect_check');
                $check_collect = request()->collect_check;
                $rescdid = SalesCollectDetails::where('collect_id',$form->collect_id)->pluck('scd_quantity','scdid')->toArray(); 
                $restid = SalesCollectDetails::where('collect_id',$form->collect_id)->pluck('stid','scdid')->toArray();
                //判斷編輯時，領貨確認變更並增減庫存
                if($recheck_collect == 0){
                    if($check_collect == "on"){
                        foreach($rescdid as $key => $scdid){
                            $retailStock = (int) Stock::find($restid[$key])->st_stock; //原本庫存數
                            //未確認=>已確認，-庫存
                            $st_stock[$key] = $retailStock - (int) $rescdid[$key];
                            $updateStockArray = [                                
                                'st_stock'      =>  $st_stock[$key],
                                'update_user'   =>  Admin::user()->id,
                                'updated_at'    =>  date('Y-m-d H:i:s'),
                            ];
                            Stock::where('stid',$restid[$key])->update($updateStockArray);
                        }
                    }
                }elseif($recheck_collect == 1){
                    if($check_collect == "off"){
                        foreach($rescdid as $key => $scdid){
                            $retailStock = (int) Stock::find($restid[$key])->st_stock; //原本庫存數
                            //已確認=>未確認，+庫存
                            $st_stock = $retailStock + (int) $rescdid[$key];
                            $updateStockArray = [                                
                                'st_stock'      =>  $st_stock,
                                'update_user'   =>  Admin::user()->id,
                                'updated_at'    =>  date('Y-m-d H:i:s'),
                            ];
                            Stock::where('stid',$restid[$key])->update($updateStockArray);
                        }
                    }
                }
                SalesCollect::where('collect_id',$form->collect_id)->update(['collect_check' => $check_collect == "on" ? 1:0]);
            });
        });
    }
}

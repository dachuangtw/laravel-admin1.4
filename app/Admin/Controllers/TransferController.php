<?php

namespace App\Admin\Controllers;

use Encore\Admin\Auth\Database\Administrator;
use App\Transfer;
use App\Warehouse;
use App\TransferDetails;
use App\ProductIndex;
use App\Stock;
use App\StockLog;
use App\ProductLog;

use Encore\Admin\Widgets\Table;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Box;
use Illuminate\Support\MessageBag;
use Encore\Admin\Auth\Permission;
use App\Admin\Extensions\ExcelExpoter;

class TransferController extends Controller
{
    use ModelForm;


    /**
     * 回傳 進貨單明細
     */
    public function transferdetail($id)
    {
        $stock = $rowWidth = $rowLeft = $rowTitle = [];
        $action = 'edit';

        $firsttime = true;
        
        $t_number = Transfer::find($id)->t_number;
        $savedDetails = TransferDetails::ofselected($t_number) ?: [];
        foreach($savedDetails as $key => $value){
            $products[$key] = ProductIndex::find($value->pid);
            $stock[$value->stid] = Stock::find($value->stid)->st_type;
        }
        $rowWidth = [33,100,150,60,80,80,80,80,110];
        $rowLeft = [0,33,133,283,343,423,503,583,693];
        $rowTitle = ['','商品編號','商品名','單位','款式','進貨數','單價','總價','備註'];
        $showprice = 'p_costprice';
        $rowTop = -30;
        $rowEvenOdd = ['even','odd'];
        $data = compact('action','products','showprice','rowWidth','rowLeft','rowTitle','rowTop','rowEvenOdd','firsttime','savedDetails','stock');
        
        return view('admin::productdetails', $data);
    }
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        Permission::check(['reader']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.transfer'));
            $content->description(trans('admin::lang.list'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.transfer')]
            );             

            $content->row(function (Row $row) {
                $row->column(4, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('product/transfer'));
                    $form->method('GET');

                    $form->dateRange('send_at[start]', 'send_at[end]', '調撥日');

                    $warehouses = Warehouse::all()->pluck('w_name', 'wid');
                    $form->select('wid_send', trans('admin::lang.warehouse'))->options(
                        
                        $warehouses
                    );
                    $form->select('wid_receive', trans('admin::lang.warehouse'))->options(
                        
                        $warehouses
                    );

                    $form->disableSubmit();
                    $form->disableReset();
                    $form->enableSearch();

                    $column->append((new Box(trans('admin::lang.search'), $form))->style('success'));
                });
            });

            $content->row(function (Row $row) {
                $row->column(12, $this->grid());
            });
        });
    }
    /**
     * View interface.
     *
     * @param $id
     * @return Content
     */
    public function view($id)
    {
        Permission::check(['reader']);

        $transfer = Transfer::find($id)->toArray();

        //忽略不顯示的欄位
        $skipArray = ['tid','created_at','updated_at','deleted_at'];
        //顯示圖片欄位
        $imgArray = [];
        
        //置換進貨倉庫id的內容
        $transfer['warehouse'] = Warehouse::find($transfer['wid'])->w_name;
        unset($transfer['wid']);
        //置換進貨人員id的內容
        $transfer['t_user'] = Administrator::find($transfer['t_user'])->name;

        $header[] = '進貨單資訊';
        foreach($transfer as $key => $value){            

            if(in_array($key,$skipArray) || empty($value))
                continue;
            
            //欄位中文化
            $newkey = trans('admin::lang.'.$key);

            //如果有換行\n改成<br>
            $rows[$newkey] = nl2br($value);            
        }

        $table = new Table($header, $rows);
        $table->class('table table-hover');

        
        $stock = $rowWidth = $rowLeft = $rowTitle = [];
        $re_number = Transfer::where('reid',$id)->pluck('re_number');
        $savedDetails = TransferDetails::ofselected($re_number) ?: [];
        foreach($savedDetails as $key => $value){
            $products[$key] = ProductIndex::where('pid',$value->pid)->get()->toArray()[0];
            $stock[$key] = Stock::find($value->stid)->st_type;
        }
        $action = 'view';
        
        $firsttime = true;
        $rowWidth = [33,100,150,60,80,80,80,80,110];
        $rowLeft = [0,33,133,283,343,423,503,583,693];
        $rowTitle = ['','商品編號','商品名','單位','款式','進貨數','單價','總價','備註'];
        $showprice = 'p_costprice';
        $rowTop = -30;
        $rowEvenOdd = ['even','odd'];
        
        $data = compact('action','products','showprice','rowWidth','rowLeft','rowTitle','rowTop','rowEvenOdd','firsttime','savedDetails','stock');
        
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
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('admin::lang.transfer'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.transfer'), 'url' => 'transfer'],
                ['text' => trans('admin::lang.edit')]
            );             

            $content->body($this->editform()->edit($id));
            $script = <<<SCRIPT
            ShowTransferDetails();
SCRIPT;
         Admin::script($script);
                
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

            $content->header(trans('admin::lang.transfer'));
            $content->description(trans('admin::lang.create'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.transfer'), 'url' => 'transfer'],
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
        return Admin::grid(Transfer::class, function (Grid $grid) {
            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->is('wid_send', trans('admin::lang.wid_send'));
                $filter->like('t_number',trans('admin::lang.t_number'));
                $filter->between('send_at', trans('admin::lang.send_at'))->date();
            });

            $grid->tid('ID')->sortable();
            $grid->send_at(trans('admin::lang.send_at'))->display(function ($send_at) {                
                return mb_substr($send_at,0,10,"utf-8");
            })->sortable();

            $grid->wid_send(trans('admin::lang.wid_send'))->display(function ($wid_send) {
                $warehouse = Warehouse::ofWarehouse($wid_send);
                return $warehouse->toArray()[0]['w_name'];
            })->sortable();

            $grid->wid_receive(trans('admin::lang.wid_receive'))->display(function ($wid_receive) {
                $warehouse = Warehouse::ofWarehouse($wid_receive);
                return $warehouse->toArray()[0]['w_name'];
            })->sortable();

            $grid->receive_at(trans('admin::lang.receive_at'))->display(function ($receive_at) {                
                return mb_substr($receive_at,0,10,"utf-8");
            })->sortable();

            $grid->t_number(trans('admin::lang.t_number'))->sortable();
            
            $grid->t_amount(trans('admin::lang.t_amount'))->display(function ($t_amount) { 
                if(strpos($t_amount,'.00'))               
                    return (int) $t_amount;
                else 
                    return $t_amount;
            })->sortable();
            

            $grid->update_user(trans('admin::lang.update_user'))->display(function ($update_user) {                
                return Administrator::find($update_user)->name;
            })->sortable();

            $grid->t_checked(trans('admin::lang.t_checked'))->value(function ($t_checked) {
                return $t_checked ? "<span class='label label-success'>Yes</span>" : "<span class='label label-danger'>No</span>";
            });

            

            //眼睛彈出視窗的Title，請設定資料庫欄位名稱
            $grid->actions(function ($actions){
                $actions->setTitleExtra('調撥單號：'); // 自訂，標題前面提示
                $actions->setTitleField(['t_number']);
            });

            //指定匯出Excel的資料庫欄位(不可使用關聯之資料庫欄位)
            $titles = ['t_number', 'send_at', 'wid_sent', 'wid_receieve', 't_amount', 'receive_user'];

            $exporter = new ExcelExpoter();
            /**
             * setDetails()參數
             * 1：資料庫欄位 array
             * 2：匯出Excel檔案名 string
             * 3：Excel製作人名稱 string
             */
            $exporter->setDetails($titles,'調撥單',Admin::user()->name);
            /**
             * setForeignKeys($foreignKeys)外部鍵設定
             */
            $foreignKeys = [
                'wid_sent'  =>  [
                    'dbname' =>  'warehouse',
                    'id' =>  'wid',
                    'target' =>  'w_name',
                ],
                'wid_receieve'  =>  [
                    'dbname' =>  'warehouse',
                    'id' =>  'wid',
                    'target' =>  'w_name',
                ],
                'receive_user'  =>  [
                    'dbname' =>  'Admin',
                    'id' =>  'receive_user',
                    'target' =>  'name',
                ],
            ];
            $exporter->setForeignKeys($foreignKeys);
            $grid->exporter($exporter);

            //顯示匯入按鈕
            // $grid->allowImport();
            $grid->model()->orderBy('tid', 'desc');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        Permission::check(['reader']);
        return Admin::form(Transfer::class, function (Form $form) {

            $form->select('wid_receive', trans('admin::lang.wid_receive'))->options(
                Warehouse::all()->pluck('w_name', 'wid')
            );
            $form->date('send_at', trans('admin::lang.send_at'));
            $form->textarea('t_notes', trans('admin::lang.notes'))->rows(2);

            $form->hidden('wid_send')->default(Admin::user()->wid);
            $form->hidden('send_user')->default(Admin::user()->id);
            $form->hidden('t_number');
            $form->hidden('t_amount');

            //btn-append有另外寫js的append功能
            $form->button('btn-danger btn-append','+ 選擇商品')->on('click','ShowModal("hasstock");');

            /**
             * 不打算修正的BUG：laravel-admin模組原本的bug
             * $form->saving()功能只在form()中有用，放在另外寫的editform()中無作用
             */
            $form->saving(function(Form $form) {
                if(empty(request()->pid)){
                    $error = new MessageBag(['title'=>'提示','message'=>'未填寫調撥商品!']);
                    return back()->withInput()->with(compact('error'));
                }
                /**
                 * 進貨單編碼規則：日期YYMMDD(6)+廠商編號XX(2)+流水號(2)，共10碼
                 */
                if(!empty(request()->wid_receive) && empty(request()->t_number)){
                    $Todaydate = (date('Y') - 1911) . date('md');
                    $wid_send = str_pad(request()->wid_send,2,"0",STR_PAD_LEFT);
                    $wid_receive = str_pad(request()->wid_receive,2,"0",STR_PAD_LEFT);

                    //取得該日該廠商進貨單號的最大值
                    $max_number = Transfer::withTrashed()->where('t_number', 'like', $Todaydate.$wid_send.$wid_receive.'%')
                    ->max('t_number');
                    
                    if(!empty($max_number)){
                        //取後兩碼做+1計算
                        $lastTwoCode = (int)mb_substr($max_number,-2,2,"utf-8");
                        $lastTwoCode++; 
                    }else{
                        $lastTwoCode = 1;
                    }
                    //前補0至兩碼
                    $lastTwoCode = str_pad($lastTwoCode,2,"0",STR_PAD_LEFT);

                    //填充到t_number欄位中
                    $form->t_number = $Todaydate.$wid_send.$wid_receive.$lastTwoCode;
                }

                $td_amount = request()->amount;
                $td_price = request()->price;
                $td_quantity = request()->quantity;
                $td_notes = request()->notes;
                $stid = request()->stid;
                $tdid = request()->tdid;
                $wid_receive = request()->wid_receive;
                $insertProductLogArray = [];
                $insertStockLogArray = [];
                $dataArray = [];
                $stidArray = [];
                $total = 0;

                /**
                 * 新增 進貨單明細/庫存
                 */
                if(request()->action == 'create'){
                    foreach(request()->pid as $key => $pid){
                        $dataArray[] = [
                            'pid'          =>  $pid,
                            't_number'     =>  $form->t_number,
                            'td_amount'    =>  $td_amount[$key],
                            'td_price'     =>  $td_price[$key],
                            'td_quantity'  =>  $td_quantity[$key],
                            'td_notes'     =>  $td_notes[$key],
                        ];
                        $stidArray[] = $stid[$key];
                        $total += $td_amount[$key];
                    }
                    if(!empty($dataArray))
                    {
                        foreach($dataArray as $key => $val){

                            // /* 商品成本變更 */
                            // $p_costprice = ProductIndex::find($val['pid'])->p_costprice;
                            // if($p_costprice != $val['td_price']){

                            //     $updateProductIndexArray = [
                            //         'p_costprice'   =>  $val['td_price'],
                            //         'last_delivery'   =>  date('Y-m-d H:i:s'),
                            //         'update_user'   =>  Admin::user()->id,
                            //     ];
                            //     ProductIndex::where('pid',$val['pid'])->update($updateProductIndexArray);
                            //     /**
                            //      * 商品價格變更紀錄 (未完成)
                            //      */
                            //     $insertProductLogArray[] = [
                            //         'pid'          =>  $val['pid'],
                            //         'pl_price1'    =>  $p_costprice,
                            //         'pl_price2'    =>  $val['td_price'],
                            //         'pl_notes'     =>  '進貨單：'.$form->t_number.'-新增',
                            //         'update_user'  =>  Admin::user()->id,
                            //         'update_at'    =>  date('Y-m-d H:i:s'),
                            //     ];
                            // }else{

                            //     $updateProductIndexArray = [
                            //         'last_delivery'   =>  date('Y-m-d H:i:s'),
                            //         'update_user'   =>  Admin::user()->id,
                            //     ];
                            //     ProductIndex::where('pid',$val['pid'])->update($updateProductIndexArray);
                            // }
                            /* 庫存變更 */
                            $retailStock = 0; //原始庫存
                            if(!empty($stidArray[$key])){ //該倉庫該商品有庫存資料
                                $stockdata = Stock::find($stidArray[$key]);
                                $retailStock = $stockdata->st_stock ?: 0;
                                $st_stock = (int) $retailStock - (int) $val['td_quantity'];

                                //更新原本的出貨倉庫存
                                $updateStockArray1 = [                                
                                    'st_stock'      =>  $st_stock,
                                    'update_user'   =>  Admin::user()->id,
                                    'updated_at'    =>  date('Y-m-d H:i:s'),
                                ];
                                Stock::find($stidArray[$key])->update($updateStockArray1);

                                //更新收貨倉的庫存

                                $receiveStockdata = Stock::where('wid',$wid_receive)->where('pid',$val['pid'])->where('st_type',$stockdata->st_type)->first();                               

                                $dataArray[$key]['stid'] = $receiveStockdata->stid;
                            }else{
                                $st_stock = $val['td_quantity'];
                                $insertStockArray = [
                                    'pid'           =>  $val['pid'],
                                    'wid'           =>  Admin::user()->wid,
                                    'st_type'       =>  '不分款',
                                    'st_stock'      =>  $st_stock,
                                    'update_user'   =>  Admin::user()->id,
                                    'created_at'    =>  date('Y-m-d H:i:s'),
                                    'update_at'     =>  date('Y-m-d H:i:s'),
                                ];
                                $dataArray[$key]['stid'] = Stock::insertGetId($insertStockArray,'stid');
                            }
                            /**
                             *  庫存變更紀錄 (未完成)
                             */
                            if($val['td_quantity'] > 0){
                                $insertStockLogArray[] = [
                                    'pid'          =>  $val['pid'],
                                    'wid'          =>  Admin::user()->wid,
                                    'stid'         =>  $dataArray[$key]['stid'],
                                    'sl_calc'      =>  '+',
                                    'sl_quantity'  =>  $val['td_quantity'],
                                    'sl_stock'     =>  $st_stock,
                                    'sl_notes'     =>  '進貨單：'.$form->t_number.'-新增',
                                    'update_user'  =>  Admin::user()->id,
                                    'update_at'    =>  date('Y-m-d H:i:s'),
                                ];
                            }
                            
                        }
                        $insertProductLogArray && ProductLog::insert($insertProductLogArray);
                        $insertStockLogArray && StockLog::insert($insertStockLogArray);

                        TransferDetails::where('t_number',$form->t_number)->delete();
                        TransferDetails::insert($dataArray);
                    }
            
                }
                /*****************************
                 * 
                 * 編輯 進貨單明細/庫存
                 * 
                 *****************************/
                elseif(request()->action == 'edit'){

                    //原本的進貨明細
                    $retailRedid = TransferDetails::where('t_number',$form->t_number)->pluck('td_quantity','tdid')->toArray();
                    // $retailTransfer = TransferDetails::where('t_number',$form->t_number)->get()->toArray();

                    //欲刪除的進貨明細 - 使用unset($deleteRedid[$tdid])移除沒有要刪除的進貨明細
                    $deleteRedid = array_keys($retailRedid);
                    
                    /**
                     *  進貨明細更新
                     *  方法1. 
                     *      刪掉原本的，insert目前頁面的明細內容
                     *  方法2.(嘗試中)
                     *      刪掉沒有的，更新原本的，insert新增加的
                     */
                    foreach(request()->pid as $key => $pid){
                        
                        //insert新增加的
                        if(empty($tdid[$key])){

                            $insertTransferArray[] = [
                                'pid'           =>  $pid,
                                't_number'     =>  $form->t_number,
                                'td_amount'    =>  $td_amount[$key],
                                'td_price'     =>  $td_price[$key],
                                'td_quantity'  =>  $td_quantity[$key],
                                'td_notes'     =>  $td_notes[$key],
                            ];

                            $stidArray[] = $stid[$key];
                        }
                        //更新原本的                        
                        elseif(isset($retailRedid[$tdid[$key]])){
                            $updateTransferArray = [
                                'td_amount'    =>  $td_amount[$key],
                                'td_price'     =>  $td_price[$key],
                                'td_quantity'  =>  $td_quantity[$key],
                                'td_notes'     =>  $td_notes[$key],
                            ];

                            //將應扣去的庫存數先儲存在陣列中
                            $deleteStockArray[$stid[$key]] = $retailRedid[$tdid[$key]] ?: 0;

                            //更新進貨明細
                            TransferDetails::find($tdid[$key])->update($updateTransferArray);

                            //此筆進貨明細不刪
                            $unsetKey = array_search($tdid[$key],$deleteRedid);
                            unset($deleteRedid[$unsetKey]);


                            /* 商品成本變更 */
                            $p_costprice = ProductIndex::find($pid)->p_costprice;
                            if($p_costprice != $td_price[$key]){

                                $updateProductIndexArray = [
                                    'p_costprice'   =>  $td_price[$key],
                                    'update_user'   =>  Admin::user()->id,
                                    'updated_at'    =>  date('Y-m-d H:i:s'),
                                ];
                                ProductIndex::where('pid',$pid)->update($updateProductIndexArray);

                                /**
                                 * 商品價格變更紀錄 (未完成)
                                 */
                                $insertProductLogArray[] = [
                                    'pid'          =>  $pid,
                                    'pl_price1'    =>  $p_costprice,
                                    'pl_price2'    =>  $td_price[$key],
                                    'pl_notes'     =>  '進貨單：'.$form->t_number.'-修改',
                                    'update_user'  =>  Admin::user()->id,
                                    'update_at'    =>  date('Y-m-d H:i:s'),
                                ];
                            }

                            /* 庫存變更 */
                            $retailStock = 0;
                            $deleteStock = 0;
                            if(!empty($stid[$key])){

                                $retailStock = (int) Stock::find($stid[$key])->st_stock ?: 0;
                                $deleteStock = $deleteStockArray[$stid[$key]] ?: 0;

                                $st_stock = $retailStock - (int) $deleteStock + (int) $td_quantity[$key];

                                $updateStockArray = [                                
                                    'st_stock'      =>  $st_stock,
                                    'update_user'   =>  Admin::user()->id,
                                    'updated_at'    =>  date('Y-m-d H:i:s'),
                                ];
                                Stock::where('stid',$stid[$key])->update($updateStockArray);

                                /**
                                 *  庫存變更紀錄 (未完成)
                                 */
                                if($st_stock - $retailStock != 0){
                                    $insertStockLogArray[] = [
                                        'pid'          =>  $pid,
                                        'wid'          =>  Admin::user()->wid,
                                        'stid'         =>  $stid[$key],
                                        'sl_calc'      =>  ($st_stock - $retailStock) > 0 ? '+' : '-',
                                        'sl_quantity'  =>  ($st_stock - $retailStock) > 0 
                                                                ? ($st_stock - $retailStock) 
                                                                : ($retailStock - $st_stock),
                                        'sl_stock'     =>  $st_stock,
                                        'sl_notes'     =>  '進貨單：'.$form->t_number.'-修改',
                                        'update_user'  =>  Admin::user()->id,
                                        'update_at'    =>  date('Y-m-d H:i:s'),
                                    ];
                                }
                            }
                        }
                        
                        $total += $td_amount[$key];
                    }

                    $insertProductLogArray && ProductLog::insert($insertProductLogArray);
                    $insertStockLogArray && StockLog::insert($insertStockLogArray);


                    /**
                     * 編輯 進貨單明細/庫存
                     * 新增明細  的  商品&庫存變更
                     */
                    if(!empty($insertTransferArray))
                    {

                        foreach($insertTransferArray as $key => $val){

                            /* 商品成本變更 */
                            $p_costprice = ProductIndex::find($val['pid'])->p_costprice;
                            if($p_costprice != $val['td_price']){

                                $updateProductIndexArray = [
                                    'p_costprice'   =>  $val['td_price'],
                                    'last_delivery'   =>  date('Y-m-d H:i:s'),
                                    'update_user'   =>  Admin::user()->id,
                                    'updated_at'    =>  date('Y-m-d H:i:s'),
                                ];
                                ProductIndex::where('pid',$val['pid'])->update($updateProductIndexArray);
                                /**
                                 * 商品價格變更紀錄 (未完成)
                                 */
                                $insertProductLogArray[] = [
                                    'pid'          =>  $val['pid'],
                                    'pl_price1'    =>  $p_costprice,
                                    'pl_price2'    =>  $val['td_price'],
                                    'pl_notes'     =>  '進貨單：'.$form->t_number.'-修改',
                                    'update_user'  =>  Admin::user()->id,
                                    'update_at'    =>  date('Y-m-d H:i:s'),
                                ];
                            }else{

                                $updateProductIndexArray = [
                                    'last_delivery'   =>  date('Y-m-d H:i:s'),
                                    'update_user'   =>  Admin::user()->id,
                                    'updated_at'    =>  date('Y-m-d H:i:s'),
                                ];
                                ProductIndex::where('pid',$val['pid'])->update($updateProductIndexArray);
                            }

                            /* 庫存變更 */
                            $retailStock = 0;
                            $deleteStock = 0;
                            if(!empty($stidArray[$key])){

                                $retailStock = Stock::find($stidArray[$key])->st_stock ?: 0;

                                $st_stock = (int) $retailStock + (int) $val['td_quantity'];

                                $updateStockArray = [                                
                                    'st_stock'   =>  $st_stock,
                                    'update_user'   =>  Admin::user()->id,
                                    'updated_at'    =>  date('Y-m-d H:i:s'),
                                ];
                                Stock::where('stid',$stidArray[$key])->update($updateStockArray);
                                $insertTransferArray[$key]['stid'] = $stidArray[$key];
                            }else{
                                $st_stock = $val['td_quantity'];
                                $insertStockArray = [
                                    'pid'           =>  $val['pid'],
                                    'wid'           =>  Admin::user()->wid,
                                    'st_type'       =>  '不分款',
                                    'st_stock'      =>  $st_stock,
                                    'update_user'   =>  Admin::user()->id,
                                    'updated_at'    =>  date('Y-m-d H:i:s'),
                                ];
                                //新增庫存資料
                                $insertTransferArray[$key]['stid'] = Stock::insertGetId($insertStockArray,'stid');
                            }
                            /**
                             *  庫存變更紀錄 (未完成)
                             */
                            if($st_stock - $retailStock != 0){
                                $insertStockLogArray[] = [
                                    'pid'          =>  $val['pid'],
                                    'wid'          =>  Admin::user()->wid,
                                    'stid'         =>  $insertTransferArray[$key]['stid'],
                                    'sl_calc'      =>  ($st_stock - $retailStock) > 0 ? '+' : '-',
                                    'sl_quantity'  =>  ($st_stock - $retailStock) > 0 
                                                            ? ($st_stock - $retailStock) 
                                                            : ($retailStock - $st_stock),
                                    'sl_stock'     =>  $st_stock,
                                    'sl_notes'     =>  '進貨單：'.$form->t_number.'-修改',
                                    'update_user'  =>  Admin::user()->id,
                                    'update_at'    =>  date('Y-m-d H:i:s'),
                                ];
                            }
                        }
                        $insertProductLogArray && ProductLog::insert($insertProductLogArray);
                        $insertStockLogArray && StockLog::insert($insertStockLogArray);
                        //新增進貨明細
                        TransferDetails::insert($insertTransferArray);
                    }
                    //刪除沒有的

                    $TransferDetails = TransferDetails::whereIn('tdid',$deleteRedid)->pluck('td_quantity','stid');

                    $insertStockLogArray = [];
                    foreach($TransferDetails as $stid => $quantity){
                        $stock = Stock::where('stid',$stid)->select('pid', 'wid','st_stock')->first();

                        $st_stock = (int) $stock->st_stock - (int) $quantity;
                        Stock::find($stid)->update(['st_stock' => $st_stock]);

                        $insertStockLogArray[] = [
                            'pid'          =>  $stock->pid,
                            'wid'          =>  $stock->wid,
                            'stid'         =>  $stid,
                            'sl_calc'      =>  '-',
                            'sl_quantity'  =>  $quantity,
                            'sl_stock'     =>  $st_stock,
                            'sl_notes'     =>  '進貨單：'.$form->t_number.'-明細刪除',
                            'update_user'  =>  Admin::user()->id,
                            'update_at'    =>  date('Y-m-d H:i:s'),
                        ];
                    }
                    $insertStockLogArray && StockLog::insert($insertStockLogArray); 
                    TransferDetails::whereIn('tdid',$deleteRedid)->delete();
                }
                $form->t_amount = $total;
            });
        })->setWidth(5);
    }
    /**
     * Make a form builder.
     * 編輯時使用的form表格
     * @return Form
     */
    protected function editform()
    {
        Permission::check(['reader']);
        return Admin::form(Transfer::class, function (Form $form) {
            $form->select('wid', trans('admin::lang.warehouse'))->options(
                Warehouse::all()->pluck('w_name', 'wid')
            );
            $form->date('t_delivery', trans('admin::lang.t_delivery'))->readOnly();
            $form->textarea('t_notes', trans('admin::lang.notes'))->rows(2);

            $form->hidden('t_number');
            $form->hidden('t_amount');
            $form->hidden('tid');

            //btn-append有另外寫js的append功能
            $form->button('btn-danger btn-append','+ 選擇商品')->on('click','ShowModal("hasstock");');
        })->setWidth(7);
    }
}

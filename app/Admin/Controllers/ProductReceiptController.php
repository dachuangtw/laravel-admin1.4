<?php

namespace App\Admin\Controllers;

use Encore\Admin\Auth\Database\Administrator;
use App\ProductReceipt;
use App\ProductSupplier;
use App\ProductReceiptDetails;
use App\ProductIndex;
use App\Warehouse;
use App\Stock;

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

class ProductReceiptController extends Controller
{
    use ModelForm;
    /**
     * 回傳 進貨單明細
     */
    public function receiptdetails($id)
    {
        $firsttime = true;
        $re_number = ProductReceipt::where('reid',$id)->pluck('re_number');
        $receiptdetails = ProductReceiptDetails::ofselected($re_number) ?: [];
        foreach($receiptdetails as $key => $value){
            $products[$key] = ProductIndex::where('pid',$value->pid)->get()->toArray()[0];
            // $stock[$key] = Stock::where('pid',$value->pid)->where('wid',$value->wid)->get()->toArray();
            $stock = Stock::where('pid',$value->pid)->where('wid',$value->wid)->pluck('st_type','stid');
        }
        $rowTop = -30;
        $rowEvenOdd = ['even','odd'];
        $data = compact('products','rowTop','rowEvenOdd','receiptdetails','firsttime');

        return view('admin::receiptedit', $data);
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

            $content->header(trans('admin::lang.product_receipt'));
            $content->description(trans('admin::lang.list'));

            $content->row(function (Row $row) {
                $row->column(4, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('product/receipt'));
                    $form->method('GET');

                    $form->dateRange('re_delivery[start]', 're_delivery[end]', '進貨日');
                    $form->select('supid', trans('admin::lang.product_supplier'))->options(
                        
                        [''=>'--- 請選擇 ---'] + ProductSupplier::all()->pluck('sup_name', 'supid')->toArray()
                    );

                    $form->text('re_number', trans('admin::lang.re_number'));

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

        $receipt = ProductReceipt::find($id)->toArray();

        //忽略不顯示的欄位
        $skipArray = ['reid','created_at','updated_at','deleted_at'];
        //顯示圖片欄位
        $imgArray = [];
        
        //置換廠商id的內容
        $receipt['product_supplier'] = ProductSupplier::find($receipt['supid'])->sup_name;
        unset($receipt['supid']);
        //置換進貨倉庫id的內容
        $receipt['warehouse'] = Warehouse::find($receipt['wid'])->w_name;
        unset($receipt['wid']);
        //置換進貨人員id的內容
        $receipt['re_user'] = Administrator::find($receipt['re_user'])->name;

        $header[] = '進貨單資訊';
        foreach($receipt as $key => $value){            

            if(in_array($key,$skipArray) || empty($value))
                continue;
            
            //欄位中文化
            $newkey = trans('admin::lang.'.$key);

            //如果有換行\n改成<br>
            $rows[$newkey] = nl2br($value);            
        }

        $table = new Table($header, $rows);
        $table->class('table table-hover');

        $re_number = ProductReceipt::where('reid',$id)->pluck('re_number');
        $receiptdetails = ProductReceiptDetails::ofselected($re_number) ?: [];
        foreach($receiptdetails as $key => $value){
            $products[$key] = ProductIndex::where('pid',$value->pid)->get()->toArray()[0];
        }
        $rowTop = -30;
        $rowEvenOdd = ['even','odd'];
        $data = compact('products','rowTop','rowEvenOdd','selected','receiptdetails');
        

        return $table->render().view('admin::receiptview', $data);
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

            $content->header(trans('admin::lang.product_receipt'));
            $content->description(trans('admin::lang.edit'));

            $content->body($this->editform()->edit($id));
            $script = <<<SCRIPT
            ShowDetails();
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

            $content->header(trans('admin::lang.product_receipt'));
            $content->description(trans('admin::lang.create'));

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
        return Admin::grid(ProductReceipt::class, function (Grid $grid) {
            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->is('supid', trans('admin::lang.product_supplier'));
                $filter->like('re_number',trans('admin::lang.re_number'));
                $filter->between('re_delivery', trans('admin::lang.re_delivery'))->date();
            });

            $grid->reid('ID')->sortable();
            $grid->re_delivery(trans('admin::lang.re_delivery'))->display(function ($re_delivery) {                
                return mb_substr($re_delivery,0,10,"utf-8");
            })->sortable();
            $grid->supid(trans('admin::lang.product_supplier'))->display(function ($supid) {
                $supplier = ProductSupplier::ofSupplier($supid);
                return $supplier->toArray()[0]['sup_name'];
            })->sortable();
            $grid->re_number(trans('admin::lang.re_number'))->sortable();;
            $grid->re_user(trans('admin::lang.re_user'))->display(function ($re_user) {                
                return Administrator::find($re_user)->name;
            })->sortable();
            
            $grid->re_amount(trans('admin::lang.re_amount'))->display(function ($re_amount) { 
                if(strpos($re_amount,'.00'))               
                    return (int) $re_amount;
                else 
                    return $re_amount;
            })->sortable();
            
            $grid->re_notes(trans('admin::lang.re_notes'))->display(function($re_notes) {
                if($re_notes)
                    return str_limit($re_notes, 10, '...');
                else
                    return '';
            });

            //眼睛彈出視窗的Title，請設定資料庫欄位名稱
            $grid->actions(function ($actions){
                $actions->setTitleExtra('進貨單號：'); // 自訂，標題前面提示
                $actions->setTitleField(['re_number']);
            });

            //指定匯出Excel的資料庫欄位(不可使用關聯之資料庫欄位)
            $titles = ['supid', 'wid', 're_number', 're_user', 're_amount', 're_notes', 're_delivery'];

            $exporter = new ExcelExpoter();
            /**
             * setDetails()參數
             * 1：資料庫欄位 array
             * 2：匯出Excel檔案名 string
             * 3：Excel製作人名稱 string
             */
            $exporter->setDetails($titles,'進貨單',Admin::user()->name);
            /**
             * setForeignKeys($foreignKeys)外部鍵設定
             */
            $foreignKeys = [
                'supid'  =>  [
                    'dbname' =>  'product_supplier',
                    'id' =>  'supid',
                    'target' =>  'sup_name',
                ],
                'wid'  =>  [
                    'dbname' =>  'warehouse',
                    'id' =>  'wid',
                    'target' =>  'w_name',
                ],
                're_user'  =>  [
                    'dbname' =>  'Admin',
                    'id' =>  're_user',
                    'target' =>  'name',
                ],
            ];
            $exporter->setForeignKeys($foreignKeys);
            $grid->exporter($exporter);

            //顯示匯入按鈕
            // $grid->allowImport();
            $grid->model()->orderBy('reid', 'desc');
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
        return Admin::form(ProductReceipt::class, function (Form $form) {
            $form->select('supid', trans('admin::lang.product_supplier'))->options(
                ProductSupplier::all()->pluck('sup_name', 'supid')
            );
            $form->date('re_delivery', trans('admin::lang.re_delivery'));//->format('YYYY/MM/DD');
            // $form->currency('re_amount', trans('admin::lang.re_amount'))->options(['digits' => 2]);
            $form->textarea('re_notes', trans('admin::lang.notes'))->rows(2);

            $form->hidden('wid')->default(Admin::user()->wid);
            $form->hidden('re_user')->default(Admin::user()->id);
            $form->hidden('re_number');
            $form->hidden('re_amount');

            //btn-append有另外寫js的append功能
            $form->button('btn-danger btn-append','+ 進貨商品')->on('click','ShowModal("product");');

            /**
             * 不打算修正的BUG：laravel-admin模組原本的bug
             * $form->saving()功能只在form()中有用，放在另外寫的editform()中無作用
             */
            $form->saving(function(Form $form) {
                /**
                 * 進貨單編碼規則：日期YYMMDD(6)+廠商編號XX(2)+流水號(2)，共10碼
                 */
                if(!empty(request()->supid) && empty(request()->re_number)){
                    $Todaydate = date('Ymd');
                    $Supplier = request()->supid;

                    //前補0至兩碼
                    $Supplier = str_pad($Supplier,2,"0",STR_PAD_LEFT);

                    //取得該日該廠商進貨單號的最大值
                    $max_number = ProductReceipt::where('re_number', 'like', $Todaydate.$Supplier.'%')
                    ->max('re_number');
                    
                    if(!empty($max_number)){
                        //取後兩碼做+1計算
                        $lastTwoCode = (int)mb_substr($max_number,-2,2,"utf-8");
                        $lastTwoCode++; 
                    }else{
                        $lastTwoCode = 1;
                    }
                    //前補0至兩碼
                    $lastTwoCode = str_pad($lastTwoCode,2,"0",STR_PAD_LEFT);

                    //填充到re_number欄位中
                    $form->re_number = $Todaydate.$Supplier.$lastTwoCode;
                }
                if(empty(request()->pid)){
                    $error = new MessageBag(['title'=>'提示','message'=>'未填寫進貨商品!']);
                    return back()->withInput()->with(compact('error'));
                }

                $red_amount = request()->red_amount;
                $red_price = request()->red_price;
                $red_quantity = request()->red_quantity;
                $red_notes = request()->red_notes;
                $stid = request()->stid;
                $redid = request()->redid;
                $dataArray = [];
                $stidArray = [];
                $total = 0;

                /**
                 * 新增 進貨單明細/庫存
                 */
                if(request()->action == 'create'){
                    foreach(request()->pid as $key => $pid){
                        $dataArray[] = [
                            'pid'           =>  $pid,
                            're_number'     =>  $form->re_number,
                            'red_amount'    =>  $red_amount[$key],
                            'red_price'     =>  $red_price[$key],
                            'red_quantity'  =>  $red_quantity[$key],
                            'red_notes'     =>  $red_notes[$key],
                        ];
                        $stidArray[] = $stid[$key];
                        $total += $red_amount[$key];
                    }
                    if(!empty($dataArray))
                    {
                        foreach($dataArray as $key => $val){

                            /* 商品成本變更 */
                            $p_costprice = ProductIndex::find($val['pid'])->p_costprice;
                            if($p_costprice != $val['red_price']){

                                $updateProductIndexArray = [
                                    'p_costprice'   =>  $val['red_price'],
                                    'last_delivery'   =>  date('Y-m-d H:i:s'),
                                    'update_user'   =>  Admin::user()->id,
                                ];
                                ProductIndex::where('pid',$val['pid'])->update($updateProductIndexArray);
                            }else{

                                $updateProductIndexArray = [
                                    'last_delivery'   =>  date('Y-m-d H:i:s'),
                                    'update_user'   =>  Admin::user()->id,
                                ];
                                ProductIndex::where('pid',$val['pid'])->update($updateProductIndexArray);
                            }
                            /* 庫存變更 */
                            $retailStock = 0; //原始庫存
                            if(!empty($stidArray[$key])){ //該倉庫該商品有庫存資料

                                $retailStock = Stock::find($stidArray[$key])->st_stock ?: 0;
                                $st_stock = (int) $retailStock + (int) $val['red_quantity'];

                                $updateStockArray = [                                
                                    'st_stock'   =>  $st_stock,
                                    'update_user'   =>  Admin::user()->id,
                                    'updated_at'    =>  date('Y-m-d H:i:s'),
                                ];
                                Stock::where('stid',$stidArray[$key])->update($updateStockArray);
                                $dataArray[$key]['stid'] = $stidArray[$key];
                            }else{
                                $st_stock = $val['red_quantity'];
                                $insertStockArray = [
                                    'pid'           =>  $val['pid'],
                                    'wid'           =>  Admin::user()->wid,
                                    'st_type'        =>  '不分款',
                                    'st_stock'       =>  $val['red_quantity'],
                                    'update_user'   =>  Admin::user()->id,
                                ];
                                $dataArray[$key]['stid'] = Stock::insertGetId($insertStockArray,'stid');
                            }
                            /**
                             *  庫存變更紀錄 (未完成)
                             */
                            // $insertStockLogArray = [
                            //     'pid'           =>  $val['pid'],
                            //     'wid'           =>  Admin::user()->wid,
                            //     'stid'           =>  '不分款',
                            //     'calc'          =>  '-',
                            //     'quantity'      =>  $val['red_quantity'],
                            //     'update_user'   =>  Admin::user()->id,
                            //     'created_at'    =>  date('Y-m-d H:i:s'),
                            // ];
                            // StockLog::insert($insertStockLogArray);
                        }
                    }

                    ProductReceiptDetails::where('re_number',$form->re_number)->delete();
                    ProductReceiptDetails::insert($dataArray);
            
                }
                /*****************************
                 * 
                 * 編輯 進貨單明細/庫存
                 * 
                 *****************************/
                elseif(request()->action == 'edit'){

                    //原本的進貨明細
                    $retailRedid = ProductReceiptDetails::where('re_number',$form->re_number)->pluck('red_quantity','redid');
                    // $retailProductReceipt = ProductReceiptDetails::where('re_number',$form->re_number)->get()->toArray();

                    //欲刪除的進貨明細 - 使用unset($deleteRedid[$redid])移除沒有要刪除的進貨明細
                    $deleteRedid = $retailRedid;

                    /**
                     *  進貨明細更新
                     *  方法1. 
                     *      刪掉原本的，insert目前頁面的明細內容
                     *  方法2.(嘗試中)
                     *      刪掉沒有的，更新原本的，insert新增加的
                     */
                    foreach(request()->pid as $key => $pid){
                        
                        //insert新增加的
                        if(empty($redid[$key])){

                            $insertProductReceiptArray[] = [
                                'pid'           =>  $pid,
                                're_number'     =>  $form->re_number,
                                'red_amount'    =>  $red_amount[$key],
                                'red_price'     =>  $red_price[$key],
                                'red_quantity'  =>  $red_quantity[$key],
                                'red_notes'     =>  $red_notes[$key],
                            ];

                            $stidArray[] = $stid[$key];
                        }
                        //更新原本的                        
                        elseif(isset($retailRedid[$redid[$key]])){
                            $updateProductReceiptArray = [
                                'red_amount'    =>  $red_amount[$key],
                                'red_price'     =>  $red_price[$key],
                                'red_quantity'  =>  $red_quantity[$key],
                                'red_notes'     =>  $red_notes[$key],
                            ];

                            //將應扣去的庫存數先儲存在陣列中
                            $deleteStockArray[$stid[$key]] = $retailRedid[$redid[$key]] ?: 0;

                            //更新進貨明細
                            ProductReceiptDetails::find($redid[$key])->update($updateProductReceiptArray);

                            //此筆進貨明細不刪
                            unset($deleteRedid[$redid[$key]]);


                            /* 商品成本變更 */
                            $p_costprice = ProductIndex::find($pid)->p_costprice;
                            if($p_costprice != $red_price[$key]){

                                $updateProductIndexArray = [
                                    'p_costprice'   =>  $red_price[$key],
                                    'update_user'   =>  Admin::user()->id,
                                    'updated_at'    =>  date('Y-m-d H:i:s'),
                                ];
                                ProductIndex::where('pid',$pid)->update($updateProductIndexArray);
                            }
                            /* 庫存變更 */
                            $retailStock = 0;
                            $deleteStock = 0;
                            if(!empty($stid[$key])){

                                $retailStock = Stock::find($stid[$key])->st_stock ?: 0;
                                $deleteStock = $deleteStockArray[$stid[$key]] ?: 0;

                                $st_stock = (int) $retailStock - (int) $deleteStock + (int) $red_quantity[$key];

                                $updateStockArray = [                                
                                    'st_stock'      =>  $st_stock,
                                    'update_user'   =>  Admin::user()->id,
                                    'updated_at'    =>  date('Y-m-d H:i:s'),
                                ];
                                Stock::where('stid',$stid[$key])->update($updateStockArray);
                            }
                        }
                        
                        $total += $red_amount[$key];

                        /**
                         * 商品價格變更紀錄 (未完成)
                         */
                        // $insertProductLogArray = [
                        //     'pid'           =>  $val['pid'],
                        //     'wid'           =>  Admin::user()->wid,
                        //     'stid'           =>  '不分款',
                        //     'calc'          =>  '-',
                        //     'quantity'      =>  $val['red_quantity'],
                        //     'update_user'   =>  Admin::user()->id,
                        //     'created_at'    =>  date('Y-m-d H:i:s'),
                        // ];
                        // StockLog::insert($insertProductLogArray);

                        
                    }

                    //扣庫存


                    /**
                     * 編輯 進貨單明細/庫存
                     * 新增明細  的  商品&庫存變更
                     */
                    if(!empty($insertProductReceiptArray))
                    {

                        foreach($insertProductReceiptArray as $key => $val){

                            /* 商品成本變更 */
                            $p_costprice = ProductIndex::find($val['pid'])->p_costprice;
                            if($p_costprice != $val['red_price']){

                                $updateProductIndexArray = [
                                    'p_costprice'   =>  $val['red_price'],
                                    'last_delivery'   =>  date('Y-m-d H:i:s'),
                                    'update_user'   =>  Admin::user()->id,
                                    'updated_at'    =>  date('Y-m-d H:i:s'),
                                ];
                                ProductIndex::where('pid',$val['pid'])->update($updateProductIndexArray);
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
                                $deleteStock = $deleteStockArray[$stidArray[$key]] ?: 0;

                                $st_stock = (int) $retailStock - (int) $deleteStock + (int) $val['red_quantity'];

                                $updateStockArray = [                                
                                    'st_stock'   =>  $st_stock,
                                    'update_user'   =>  Admin::user()->id,
                                    'updated_at'    =>  date('Y-m-d H:i:s'),
                                ];
                                Stock::where('stid',$stidArray[$key])->update($updateStockArray);
                            }else{
                                $st_stock = $val['red_quantity'];
                                $insertStockArray = [
                                    'pid'           =>  $val['pid'],
                                    'wid'           =>  Admin::user()->wid,
                                    'st_type'       =>  '不分款',
                                    'st_stock'      =>  $val['red_quantity'],
                                    'update_user'   =>  Admin::user()->id,
                                    'updated_at'    =>  date('Y-m-d H:i:s'),
                                ];
                                //新增庫存資料
                                $insertProductReceiptArray[$key]['stid'] = Stock::insertGetId($insertStockArray,'stid');
                            }
                            /**
                             *  庫存變更紀錄 (未完成)
                             */
                            // $insertStockLogArray = [
                            //     'pid'           =>  $val['pid'],
                            //     'wid'           =>  Admin::user()->wid,
                            //     'stid'           =>  '不分款',
                            //     'calc'          =>  '-',
                            //     'quantity'      =>  $val['red_quantity'],
                            //     'update_user'   =>  Admin::user()->id,
                            //     'created_at'    =>  date('Y-m-d H:i:s'),
                            // ];
                            // StockLog::insert($insertStockLogArray);
                        }
                        //新增進貨明細
                        ProductReceiptDetails::insert($insertProductReceiptArray);
                    }
                    //刪除沒有的
                    ProductReceiptDetails::whereIn('redid',$deleteRedid)->delete();
                }
                $form->re_amount = $total;
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
        return Admin::form(ProductReceipt::class, function (Form $form) {
            $form->select('supid', trans('admin::lang.product_supplier'))->options(
                ProductSupplier::all()->pluck('sup_name', 'supid')
            )->readOnly();
            // $form->display('supid', trans('admin::lang.product_supplier'))->with(function($supid) {
            //     return ProductSupplier::where('supid',$supid)->pluck('sup_name')[0];
            // });
            $form->date('re_delivery', trans('admin::lang.re_delivery'))->readOnly();
            $form->textarea('re_notes', trans('admin::lang.notes'))->rows(2);

            $form->hidden('re_number');
            $form->hidden('re_amount');
            $form->hidden('reid');

            //btn-append有另外寫js的append功能
            $form->button('btn-danger btn-append','+ 進貨商品')->on('click','ShowModal("product");');
        })->setWidth(7);
    }
}

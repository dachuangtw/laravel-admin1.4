<?php

namespace App\Admin\Controllers;

use Encore\Admin\Auth\Database\Administrator;
use App\ProductReceipt;
use App\ProductSupplier;
use App\ProductReceiptDetails;
use App\ProductIndex;
use App\Warehouse;
use App\Stock;
use App\StockLog;
use App\ProductLog;
use App\StockCategory;

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
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        Permission::check(['ProductReceipt-Reader']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.product_receipt'));
            $content->description(trans('admin::lang.list'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.product_receipt')]
            );

            $content->row(function (Row $row) {
                $row->column(4, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('product/receipt'));
                    $form->method('GET');

                    $form->dateRange('re_delivery[start]', 're_delivery[end]', '進貨日');
                    $form->select('supid', trans('admin::lang.product_supplier'))->options(

                        ProductSupplier::all()->pluck('sup_name', 'supid')
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
        Permission::check(['ProductReceipt-Reader']);

        $receipt = ProductReceipt::find($id)->toArray();

        //忽略不顯示的欄位
        $skipArray = ['reid','created_at','updated_at','deleted_at'];
        //顯示圖片欄位
        $imgArray = [];

        //置換廠商id的內容
        $receipt['product_supplier'] = ProductSupplier::find($receipt['supid'])->sup_name;
        unset($receipt['supid']);
        //置換進貨倉庫id的內容
        $receipt['warehouse'] = Warehouse::find($receipt['wid'])->name;
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

        $stock = $rowWidth = $rowLeft = $rowTitle = [];
        $re_number = ProductReceipt::where('reid',$id)->pluck('re_number');
        $savedDetails = ProductReceiptDetails::ofselected($re_number) ?: [];
        foreach($savedDetails as $key => $value){
            $products[$key] = ProductIndex::where('pid',$value->pid)->get()->toArray()[0];
            // $stock[$key] = Stock::find($value->stid)->st_type;
        }
        $action = 'view';

        $firsttime = true;
        $inputtext = true;

        // $rowWidth = [33,100,150,60,80,80,80,80,110];
        // $rowLeft = [0,33,133,283,343,423,503,583,663];
        $rowWidth = [33,180,150,60,80,80,80,110];
        $rowLeft = [0,33,213,363,423,503,583,663];
        $rowTitle = ['','商品編號','商品名','單位',/*'款式',*/'進貨數','單價','總價','備註'];
        $showPrice = 'red_price';
        $showQuantity = 'red_quantity';
        $showAmount = 'red_amount';
        $showNotes = 'red_notes';
        $rowTop = -30;
        $rowEvenOdd = ['even','odd'];

        $data = compact('action','products','showPrice','showQuantity','showAmount','showNotes','rowWidth','rowLeft','rowTitle','rowTop','rowEvenOdd','firsttime','inputtext','savedDetails','stock');

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
        Permission::check(['ProductReceipt-Editor']);
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('admin::lang.product_receipt'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.product_receipt'), 'url' => '/product/receipt'],
                ['text' => trans('admin::lang.edit')]
            );

            $content->body($this->form($id)->edit($id));
//             $script = <<<SCRIPT
//             ShowReceiptDetails('$id');
// SCRIPT;
//          Admin::script($script);

        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        Permission::check(['ProductReceipt-Creator']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.product_receipt'));
            $content->description(trans('admin::lang.create'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.product_receipt'), 'url' => '/product/receipt'],
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
                    'dbname' =>  'warehouses',
                    'id' =>  'id',
                    'target' =>  'name',
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
    protected function form($id = null)
    {
        return Admin::form(ProductReceipt::class, function (Form $form) use ($id){
            $StockCategory = StockCategory::all()->pluck('sc_name', 'sc_number') ?: [];
            $options = '';
            $savedDetails = $products = [];
            foreach($StockCategory as $sc_number => $sc_name){
                $options .= '<option value="' . $sc_number .'">'. $sc_name .'</option>';
            }            
            if(!empty($id)){
                $re_number = ProductReceipt::where('reid',$id)->pluck('re_number');
                $savedDetails = ProductReceiptDetails::ofselected($re_number) ?: [];
                foreach($savedDetails as $key => $value){
                    $savedDetails[$key]['sumcostprice'] = (int)$value['red_quantity'] * (int)$value['red_price'];
                    $products[$key] = ProductIndex::find($value['pid']);
                    $savedDetails[$key]['sumsalesprice'] = (int)$value['red_quantity'] * (int)$products[$key]['p_salesprice'];
                    $products[$key]['category'] = substr($products[$key]['p_number'],1,1);
                }
            }
            $form->setView('admin.productreceipt',['StockCategory'=>$StockCategory,'options'=>$options,'savedDetails'=>$savedDetails,'products'=>$products]);
            if(empty($id)){
                $form->select('supid', trans('admin::lang.product_supplier'))->options(
                    ProductSupplier::all()->pluck('sup_name', 'supid')
                );
                $form->date('re_delivery', trans('admin::lang.re_delivery'));
                $form->hidden('action')->default('create');
            }else{
                $form->select('supid', trans('admin::lang.product_supplier'))->options(
                    ProductSupplier::all()->pluck('sup_name', 'supid')
                )->readOnly();
                $form->date('re_delivery', trans('admin::lang.re_delivery'))->readOnly();
                $form->hidden('action')->default('edit');
            }
            $form->textarea('re_notes', trans('admin::lang.notes'))->rows(2);
            $form->divide();
            $form->hidden('wid')->default(Admin::user()->wid);
            $form->hidden('re_user')->default(Admin::user()->id);
            $form->hidden('re_number');
            $form->hidden('re_amount');

            //btn-append有另外寫js的append功能
            // $form->button('btn-danger btn-append','+ 進貨商品')->on('click','ShowModal("product");');

            /**
             * 不打算修正的BUG：laravel-admin模組原本的bug
             * $form->saving()功能只在form()中有用，放在另外寫的editform()中無作用
             */
            $form->saving(function(Form $form) {
                if(empty(request()->p_name)){
                    $error = new MessageBag(['title'=>'提示','message'=>'未填寫進貨商品!']);
                    return back()->withInput()->with(compact('error'));
                }elseif(empty(request()->supid) && request()->action == 'create'){
                    $error = new MessageBag(['title'=>'提示','message'=>'未選擇進貨廠商!']);
                    return back()->withInput()->with(compact('error'));
                }
                /**
                 * 進貨單編碼規則：日期YYYYMMDD(8)+廠商編號XX(2)+流水號(2)，共12碼
                 */
                if(!empty(request()->supid) && empty(request()->re_number)){
                    $Todaydate = date('Ymd');
                    $Supplier = request()->supid;

                    //前補0至兩碼
                    $Supplier = str_pad($Supplier,2,"0",STR_PAD_LEFT);

                    //取得該日該廠商進貨單號的最大值
                    $max_number = ProductReceipt::withTrashed()->where('re_number', 'like', $Todaydate.$Supplier.'%')
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

                $category = request()->category;
                $pid = request()->pid;
                $p_name = request()->p_name;
                // $retailprice = request()->retailprice; //售價
                // $southprice = request()->southprice; //南台價
                $salesprice = request()->salesprice; //業務價
                $red_price = $costprice = request()->costprice; //成本價
                $red_amount = request()->sumcostprice;
                $red_quantity = request()->quantity;
                $red_notes = request()->notes;

                $stid = request()->stid;
                $redid = request()->redid;

                $insertProductIndexArray = [];
                $insertProductLogArray = [];
                $insertStockArray = [];
                $insertStockLogArray = $insertStockLogArray2 = [];
                $dataArray = $dataArray2 = [];
                $total = 0;

                /**
                 * 新增 進貨單明細/庫存
                 */
                if(request()->action == 'create'){
                    foreach($p_name as $key => $value){
                        //如果商品不存在則新增商品
                        if(empty($pid[$key])){
                            /**
                             * 商品編碼產生 (13碼)
                             *  1	廠商編碼(1碼)
                             *  2	分類編碼(1碼)
                             *  3	西元年(最後1碼)
                             *  4	月份(2碼)
                             *  5	月份(2碼)
                             *  6	日(2碼)
                             *  7	日(2碼)
                             *  8	流水號(2碼)
                             *  9	流水號(2碼)
                             *  10	業務價(3碼) = 千位數 1 or 亂數 0、2~9
                             *  11	業務價(3碼)
                             *  12	業務價(3碼)
                             *  13	檢查碼 = 3~6碼x2 + 7~10碼 - 11~12碼x3 - 第9碼x7 計算後的 個位數 
                             */
                            $N1 = ProductSupplier::find(request()->supid)->sup_number;
                            $N2 = $category[$key];
                            $Deliverydate = request()->re_delivery;
                            $Y = substr($Deliverydate, 3,1);
                            $M = substr($Deliverydate, 5,2);
                            $D = substr($Deliverydate, 8,2);
                            $N3to7 = $Y.$M.$D;
                            $N8to9 = '00'; //不重複時的預設值
                            if($salesprice[$key] >= 1000){
                                $N10 = 1;
                                $N11to12 = substr($salesprice[$key], 1,2);
                            }else{
                                $N10 = rand(1,9);
                                $N10 = ($N10 === 1) ? 0 : $N10; //把1用0取代掉
                                $N11to12 = floor($salesprice[$key] / 10);
                            }

                            //前補0至兩碼
                            $N11to12 = str_pad($N11to12, 2, "0", STR_PAD_LEFT);

                            /**
                             * 流水號$N8to9判斷重複與新流水號產生
                             * Step1. 找出N1~N7 以及 N10~N12相同 的 流水號們N8~N9 使用字串擷取函數SUBSTRING(字串,起始,位數)
                             * Step2. 最新的流水號$N8to9 = 取得流水號的最大值+1
                             */
                            $like_query = $N1.$N2.$N3to7.'__'.$N10.$N11to12.'_';
                            $p_collection = ProductIndex::where('p_number','like',$like_query)->pluck('p_number');
                            if ($p_collection) {
                                $NewCollection = $p_collection->map(function ($item, $key) {
                                    return substr($item, 7, 2);
                                });
                                //現有流水號最大值+1
                                $N8to9 = (int)($NewCollection->max()) + 1;
                                //超出99報出錯
                                if($N8to9 > 99){
                                    $error = new MessageBag(['title'=>'編碼溢出錯誤','message'=>'當日同分類同價格商品過多!']);
                                    return back()->withInput()->with(compact('error'));
                                }
                                //前補0至兩碼
                                $N8to9 = str_pad($N8to9, 2, "0", STR_PAD_LEFT);
                            }
                            $N1to12 = $N1.$N2.$N3to7.$N8to9.$N10.$N11to12;
                            $N13 = (int)substr($N1to12, 2, 4) * 2 + (int)substr($N1to12, 6, 4) - (int)substr($N1to12, 10, 2) * 3 - (int)substr($N1to12, 8, 1) * 7;
                            //取個位數
                            $N13 = substr($N13,-1);

                            /* 新增商品 */
                            $insertProductIndexArray = [
                                'p_number'      => $N1to12.$N13,
                                'p_name'        => $value,
                                // 'p_retailprice' => $retailprice[$key],
                                // 'p_southprice'  => $southprice[$key],
                                'p_salesprice'  => $salesprice[$key],
                                'p_costprice'   => $costprice[$key],
                                'p_notes'       => $red_notes[$key],
                                'update_user'   => Admin::user()->id,
                                'last_delivery' => $Deliverydate,
                                'created_at'    => date('Y-m-d H:i:s'),
                                'showsales'     => 1,
                            ];
                            //因為要撈資料庫判斷重複所以insert指令在這裡執行
                            $NewPid = ProductIndex::insertGetId($insertProductIndexArray,'pid');

                            $insertStockArray[] = [
                                'pid'           =>  $NewPid,
                                'wid'           =>  Admin::user()->wid,
                                'st_stock'      =>  $red_quantity[$key],
                                'st_unit'       =>  '每人',
                                'update_user'   =>  Admin::user()->id,
                                'created_at'    =>  date('Y-m-d H:i:s'),
                                'updated_at'    =>  date('Y-m-d H:i:s'),
                            ];
                            $insertStockLogArray[] = [
                                'pid'           =>  $NewPid,
                                'wid'          =>  Admin::user()->wid,
                                'sl_calc'      =>  '+',
                                'sl_quantity'  =>  $red_quantity[$key],
                                'sl_stock'     =>  $red_quantity[$key],
                                'sl_notes'     =>  '進貨單：'.$form->re_number.'-新增',
                                'update_user'  =>  Admin::user()->id,
                                'updated_at'    =>  date('Y-m-d H:i:s'),
                            ];

                            $dataArray[] = [
                                'pid'           =>  $NewPid,
                                're_number'     =>  $form->re_number,
                                'red_amount'    =>  $red_amount[$key],
                                'red_price'     =>  $red_price[$key],
                                'red_quantity'  =>  $red_quantity[$key],
                                'red_notes'     =>  $red_notes[$key],
                            ];
                            
                        }else{ //如果商品存在，則更新商品及庫存資料
                            /* 庫存變更 */
                            //原始庫存                            
                            $retailStock = Stock::where('pid',$pid[$key])->where('wid',Admin::user()->wid)->first()->select('st_stock','stid');
                            if(empty($retailStock)){ //無庫存資料，新增庫存
                                $insertStockArray2 = [
                                    'pid'           =>  $pid[$key],
                                    'wid'           =>  Admin::user()->wid,
                                    'st_stock'      =>  $red_quantity[$key],
                                    'st_unit'       =>  '每人',
                                    'update_user'   =>  Admin::user()->id,
                                    'created_at'    =>  date('Y-m-d H:i:s'),
                                    'updated_at'    =>  date('Y-m-d H:i:s'),
                                ];
                                $NewStid = Stock::insertGetId($insertStockArray2,'stid');
                                $insertStockLogArray2[] = [
                                    'pid'          =>  $pid[$key],
                                    'stid'         =>  $NewStid,
                                    'wid'          =>  Admin::user()->wid,
                                    'sl_calc'      =>  '+',
                                    'sl_quantity'  =>  $red_quantity[$key],
                                    'sl_stock'     =>  $red_quantity[$key],
                                    'sl_notes'     =>  '進貨單：'.$form->re_number.'-新增',
                                    'update_user'  =>  Admin::user()->id,
                                    'updated_at'   =>  date('Y-m-d H:i:s'),
                                ];
                                
                            }else{ //已有庫存資料，更新庫存
                                $st_stock = (int) $retailStock->st_stock + (int) $red_quantity[$key];

                                $updateStockArray = [
                                    'st_stock'      =>  $st_stock,
                                    'update_user'   =>  Admin::user()->id,
                                    'updated_at'    =>  date('Y-m-d H:i:s'),
                                ];
                                Stock::find($retailStock->stid)->update($updateStockArray);
                                $insertStockLogArray2[] = [
                                    'pid'          =>  $pid[$key],
                                    'stid'         =>  $retailStock->stid,
                                    'wid'          =>  Admin::user()->wid,
                                    'sl_calc'      =>  '+',
                                    'sl_quantity'  =>  $red_quantity[$key],
                                    'sl_stock'     =>  $st_stock,
                                    'sl_notes'     =>  '進貨單：'.$form->re_number.'-新增',
                                    'update_user'  =>  Admin::user()->id,
                                    'updated_at'   =>  date('Y-m-d H:i:s'),
                                ];
                            }
                            $dataArray2[] = [
                                'pid'           =>  $pid[$key],
                                'stid'          =>  $retailStock->stid,
                                're_number'     =>  $form->re_number,
                                'red_amount'    =>  $red_amount[$key],
                                'red_price'     =>  $red_price[$key],
                                'red_quantity'  =>  $red_quantity[$key],
                                'red_notes'     =>  $red_notes[$key],
                            ];
                        }
                        $total += $red_amount[$key];
                    }
                    foreach ($insertStockArray as $key => $eachinsert) {
                        $NewStid = Stock::insertGetId($eachinsert,'stid');
                        $insertStockLogArray[$key]['stid'] = $NewStid;
                        $dataArray[$key]['stid'] = $NewStid;
                    }
                    $dataArray && ProductReceiptDetails::insert($dataArray);
                    $insertStockLogArray && StockLog::insert($insertStockLogArray);

                    
                    $dataArray2 && ProductReceiptDetails::insert($dataArray2);
                    $insertStockLogArray2 && StockLog::insert($insertStockLogArray2);
                }
                /*****************************
                 * 
                 * 編輯 進貨單明細/庫存
                 * 
                 * 1.商品不存在->建立商品->建立進貨單->建立庫存
                 * 
                 * 2.商品存在_＿進貨單明細不存在____庫存不存在->新增庫存____新增進貨單明細
                 *           |                 |_庫存存在->更新庫存__|
                 *           |
                 *           |_進貨單明細存在->更新庫存->更新進貨單明細
                 * 
                 * ##更新進貨單明細：刪掉沒有的，更新原本的，insert新增加的
                 * 
                 *****************************/
                elseif(request()->action == 'edit'){
                    //原本的進貨明細
                    $retailRedid = ProductReceiptDetails::where('re_number',$form->re_number)->pluck('redid')->toArray();
                    // 欲刪除的進貨明細 - 使用unset($deleteRedid[$redid])移除沒有要刪除的進貨明細
                    $deleteRedid = array_keys($retailRedid);

                    foreach ($p_name as $key => $value) {
                        //如果商品不存在則新增商品
                        if (empty($pid[$key])) {
                            /**
                             * 商品編碼產生 (13碼)
                             *  1	廠商編碼(1碼)
                             *  2	分類編碼(1碼)
                             *  3	西元年(最後1碼)
                             *  4	月份(2碼)
                             *  5	月份(2碼)
                             *  6	日(2碼)
                             *  7	日(2碼)
                             *  8	流水號(2碼)
                             *  9	流水號(2碼)
                             *  10	業務價(3碼) = 千位數 1 or 亂數 0、2~9
                             *  11	業務價(3碼)
                             *  12	業務價(3碼)
                             *  13	檢查碼 = 3~6碼x2 + 7~10碼 - 11~12碼x3 - 第9碼x7  計算後的 個位數
                             */
                            $N1 = ProductSupplier::find(request()->supid)->sup_number;
                            $N2 = $category[$key];
                            $Deliverydate = request()->re_delivery;
                            $Y = substr($Deliverydate, 3, 1);
                            $M = substr($Deliverydate, 5, 2);
                            $D = substr($Deliverydate, 8, 2);
                            $N3to7 = $Y.$M.$D;
                            $N8to9 = '00'; //不重複時的預設值
                            if ($salesprice[$key] >= 1000) {
                                $N10 = 1;
                                $N11to12 = substr($salesprice[$key], 1, 2);
                            } else {
                                $N10 = rand(1, 9);
                                $N10 = ($N10 === 1) ? 0 : $N10; //把1用0取代掉
                                $N11to12 = floor($salesprice[$key] / 10);
                            }
                            
                            //前補0至兩碼
                            $N11to12 = str_pad($N11to12, 2, "0", STR_PAD_LEFT);

                            /**
                             * 流水號$N8to9判斷重複與新流水號產生
                             * Step1. 找出N1~N7 以及 N10~N12相同 的 流水號們N8~N9 使用字串擷取函數SUBSTRING(字串,起始,位數)
                             * Step2. 最新的流水號$N8to9 = 取得流水號的最大值+1
                             */
                            $like_query = $N1.$N2.$N3to7.'__'.$N10.$N11to12.'_';
                            $p_collection = ProductIndex::where('p_number', 'like', $like_query)->pluck('p_number');
                            if ($p_collection) {
                                $NewCollection = $p_collection->map(function ($item, $key) {
                                    return substr($item, 7, 2);
                                });
                                //現有流水號最大值+1
                                $N8to9 = (int)($NewCollection->max()) + 1;
                                //超出99報出錯
                                if ($N8to9 > 99) {
                                    $error = new MessageBag(['title'=>'編碼溢出錯誤','message'=>'當日同分類同價格商品過多!']);
                                    return back()->withInput()->with(compact('error'));
                                }
                                //前補0至兩碼
                                $N8to9 = str_pad($N8to9, 2, "0", STR_PAD_LEFT);
                            }
                            $N1to12 = $N1.$N2.$N3to7.$N8to9.$N10.$N11to12;
                            $N13 = (int)substr($N1to12, 2, 4) * 2 + (int)substr($N1to12, 6, 4) - (int)substr($N1to12, 10, 2) * 3 - (int) substr($N1to12, 8, 1) * 7;
                            //取個位數
                            $N13 = substr($N13, -1);
                            
                            /* 新增商品 */
                            $insertProductIndexArray = [
                                'p_number'      => $N1to12.$N13,
                                'p_name'        => $value,
                                // 'p_retailprice' => $retailprice[$key],
                                // 'p_southprice'  => $southprice[$key],
                                'p_salesprice'  => $salesprice[$key],
                                'p_costprice'   => $costprice[$key],
                                'p_notes'       => $red_notes[$key],
                                'update_user'   => Admin::user()->id,
                                'last_delivery' => $Deliverydate,
                                'created_at'    => date('Y-m-d H:i:s'),
                                'showsales'     => 1,
                            ];
                            //因為要撈資料庫判斷重複所以insert指令在這裡執行
                            $NewPid = ProductIndex::insertGetId($insertProductIndexArray, 'pid');

                            $insertStockArray[] = [
                                'pid'           =>  $NewPid,
                                'wid'           =>  Admin::user()->wid,
                                'st_stock'      =>  $red_quantity[$key],
                                'st_unit'       =>  '每人',
                                'update_user'   =>  Admin::user()->id,
                                'created_at'    =>  date('Y-m-d H:i:s'),
                                'updated_at'    =>  date('Y-m-d H:i:s'),
                            ];
                            $insertStockLogArray[] = [
                                'pid'          =>  $NewPid,
                                'wid'          =>  Admin::user()->wid,
                                'sl_calc'      =>  '+',
                                'sl_quantity'  =>  $red_quantity[$key],
                                'sl_stock'     =>  $red_quantity[$key],
                                'sl_notes'     =>  '進貨單：'.$form->re_number.'-編輯',
                                'update_user'  =>  Admin::user()->id,
                                'updated_at'   =>  date('Y-m-d H:i:s'),
                            ];

                            $dataArray[] = [
                                'pid'           =>  $NewPid,
                                're_number'     =>  $form->re_number,
                                'red_amount'    =>  $red_amount[$key],
                                'red_price'     =>  $red_price[$key],
                                'red_quantity'  =>  $red_quantity[$key],
                                'red_notes'     =>  $red_notes[$key],
                            ];
                        }else{
                            //商品pid存在
                            //判斷庫存存在不存在
                            $retailStock = Stock::where('pid',$pid[$key])->where('wid',Admin::user()->wid)->select('st_stock','stid')->first();
                            //進貨單明細redid不存在
                            if(empty($redid[$key])){
                                if(empty($retailStock)){ //無庫存資料，新增庫存
                                    $insertStockArray2 = [
                                        'pid'           =>  $pid[$key],
                                        'wid'           =>  Admin::user()->wid,
                                        'st_stock'      =>  $red_quantity[$key],
                                        'st_unit'       =>  '每人',
                                        'update_user'   =>  Admin::user()->id,
                                        'created_at'    =>  date('Y-m-d H:i:s'),
                                        'updated_at'    =>  date('Y-m-d H:i:s'),
                                    ];
                                    $NewStid = Stock::insertGetId($insertStockArray2,'stid');
                                    $insertStockLogArray2[] = [
                                        'pid'          =>  $pid[$key],
                                        'stid'         =>  $NewStid,
                                        'wid'          =>  Admin::user()->wid,
                                        'sl_calc'      =>  '+',
                                        'sl_quantity'  =>  $red_quantity[$key],
                                        'sl_stock'     =>  $red_quantity[$key],
                                        'sl_notes'     =>  '進貨單：'.$form->re_number.'-修改',
                                        'update_user'  =>  Admin::user()->id,
                                        'updated_at'   =>  date('Y-m-d H:i:s'),
                                    ];
                                    $retailStock->stid = $NewStid;
                                }else{
                                    //庫存存在，更新庫存
                                    $st_stock = (int) $retailStock->st_stock + (int) $red_quantity[$key];
                                    $updateStockArray = [
                                        'st_stock'      =>  $st_stock,
                                        'update_user'   =>  Admin::user()->id,
                                        'updated_at'    =>  date('Y-m-d H:i:s'),
                                    ];
                                    Stock::find($retailStock->stid)->update($updateStockArray);
                                    $insertStockLogArray2[] = [
                                        'pid'          =>  $pid[$key],
                                        'stid'         =>  $retailStock->stid,
                                        'wid'          =>  Admin::user()->wid,
                                        'sl_calc'      =>  '+',
                                        'sl_quantity'  =>  $red_quantity[$key],
                                        'sl_stock'     =>  $st_stock,
                                        'sl_notes'     =>  '進貨單：'.$form->re_number.'-修改',
                                        'update_user'  =>  Admin::user()->id,
                                        'updated_at'   =>  date('Y-m-d H:i:s'),
                                    ];
                                }

                                $insertProductReceiptArray[] = [
                                    'pid'           =>  $pid[$key],
                                    'stid'          =>  $retailStock->stid,
                                    're_number'     =>  $form->re_number,
                                    'red_amount'    =>  $red_amount[$key],
                                    'red_price'     =>  $red_price[$key],
                                    'red_quantity'  =>  $red_quantity[$key],
                                    'red_notes'     =>  $red_notes[$key],
                                ];
                            }else{ //進貨單明細存在
                                /* 更新庫存 */
                                //將應扣去的庫存數先抓出來
                                $deleteStock = ProductReceiptDetails::find($redid[$key])->red_quantity ?: 0;
                                $st_stock = (int)$retailStock->st_stock - (int)$deleteStock + (int)$red_quantity[$key];
                                //如果庫存有變化才更新
                                if($st_stock - $retailStock->st_stock != 0){
                                    $updateStockArray = [
                                        'st_stock'      =>  $st_stock,
                                        'update_user'   =>  Admin::user()->id,
                                        'updated_at'    =>  date('Y-m-d H:i:s'),
                                    ];
                                    Stock::find($retailStock->stid)->update($updateStockArray);
                                    /**
                                     *  庫存變更紀錄
                                     */
                                    $insertStockLogArray[] = [
                                        'pid'          =>  $pid,
                                        'wid'          =>  Admin::user()->wid,
                                        'stid'         =>  $retailStock->stid,
                                        'sl_calc'      =>  ($st_stock - $retailStock->st_stock) > 0 ? '+' : '-',
                                        'sl_quantity'  =>  abs($st_stock - $retailStock->st_stock),
                                        'sl_stock'     =>  $st_stock,
                                        'sl_notes'     =>  '進貨單：'.$form->re_number.'-修改',
                                        'update_user'  =>  Admin::user()->id,
                                        'updated_at'   =>  date('Y-m-d H:i:s'),
                                    ];
                                }

                                //更新進貨單明細
                                $updateProductReceiptArray = [
                                    'red_amount'    =>  $red_amount[$key],
                                    'red_price'     =>  $red_price[$key],
                                    'red_quantity'  =>  $red_quantity[$key],
                                    'red_notes'     =>  $red_notes[$key],
                                ];
                                ProductReceiptDetails::find($redid[$key])->update($updateProductReceiptArray);

                                //此筆進貨明細不刪
                                $unsetKey = array_search($redid[$key],$deleteRedid);
                                unset($deleteRedid[$unsetKey]);

                                /* 商品成本變更 */
                                $p_costprice = ProductIndex::find($pid[$key])->p_costprice;
                                if($p_costprice != $red_price[$key]){

                                    $updateProductIndexArray = [
                                        'p_costprice'   =>  $red_price[$key],
                                        'p_salesprice'  =>  $salesprice[$key],
                                        'update_user'   =>  Admin::user()->id,
                                        'updated_at'    =>  date('Y-m-d H:i:s'),
                                    ];
                                    ProductIndex::find($pid[$key])->update($updateProductIndexArray);

                                    /**
                                     * 商品價格變更紀錄
                                     */
                                    $insertProductLogArray[] = [
                                        'pid'          =>  $pid,
                                        'pl_price1'    =>  $p_costprice,
                                        'pl_price2'    =>  $red_price[$key],
                                        'pl_notes'     =>  '進貨單：'.$form->re_number.'-修改',
                                        'update_user'  =>  Admin::user()->id,
                                        'updated_at'   =>  date('Y-m-d H:i:s'),
                                    ];
                                }
                            }
                            $total += $red_amount[$key];
                        }
                    }

                    //刪除沒有的
                    $ProductReceiptDetails = ProductReceiptDetails::whereIn('redid',$deleteRedid)->pluck('red_quantity','stid');
                    foreach($ProductReceiptDetails as $stid => $quantity){
                        $stock = Stock::where('stid',$stid)->select('pid', 'wid','st_stock')->first();

                        $st_stock = (int) $stock->st_stock - (int) $quantity;
                        Stock::find($stid)->update(['st_stock' => $st_stock]);

                        $insertStockLogArray2[] = [
                            'pid'          =>  $stock->pid,
                            'wid'          =>  $stock->wid,
                            'stid'         =>  $stid,
                            'sl_calc'      =>  '-',
                            'sl_quantity'  =>  $quantity,
                            'sl_stock'     =>  $st_stock,
                            'sl_notes'     =>  '進貨單：'.$form->re_number.'-明細刪除',
                            'update_user'  =>  Admin::user()->id,
                            'updated_at'   =>  date('Y-m-d H:i:s'),
                        ];
                    }

                    foreach ($insertStockArray as $key => $eachinsert) {
                        $NewStid = Stock::insertGetId($eachinsert,'stid');
                        $insertStockLogArray[$key]['stid'] = $NewStid;
                        $dataArray[$key]['stid'] = $NewStid;
                    }
                    $dataArray && ProductReceiptDetails::insert($dataArray);
                    $insertStockLogArray && StockLog::insert($insertStockLogArray);
                    $dataArray2 && ProductReceiptDetails::insert($dataArray2);
                    $insertStockLogArray2 && StockLog::insert($insertStockLogArray2);
                    
                    ProductReceiptDetails::whereIn('redid',$deleteRedid)->delete();
                }
                $form->re_amount = $total;
                $form->update_user = Admin::user()->id;
            });
            $form->ignore(['action']);
        })->setWidth(5);
    }
}

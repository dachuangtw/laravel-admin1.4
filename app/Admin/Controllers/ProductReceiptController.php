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

            $content->body($this->editform()->edit($id));
            $script = <<<SCRIPT
            ShowReceiptDetails('$id');
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
        return Admin::form(ProductReceipt::class, function (Form $form) {
            $StockCategory = StockCategory::all()->pluck('sc_name', 'sc_number');
            $options = '';
            foreach($StockCategory as $sc_number => $sc_name){
                $options .= '<option value="' . $sc_number .'">'. $sc_name .'</option>';
            }
            $form->setView('admin.productreceipt',['StockCategory'=>$options]);
            $form->select('supid', trans('admin::lang.product_supplier'))->options(
                ProductSupplier::all()->pluck('sup_name', 'supid')
            );
            $form->date('re_delivery', trans('admin::lang.re_delivery'));//->format('YYYY/MM/DD');
            // $form->currency('re_amount', trans('admin::lang.re_amount'))->options(['digits' => 2]);
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
                $salesprice = request()->salesprice; //業務價
                $southprice = request()->southprice; //南台價
                $retailprice = request()->retailprice; //售價
                $red_amount = request()->sumcostprice;
                $red_price = $costprice = request()->costprice; //成本價
                $red_quantity = request()->quantity;
                $red_notes = request()->notes;

                $stid = request()->stid;
                $redid = request()->redid;

                $insertProductIndexArray = [];
                $insertProductLogArray = [];
                $insertStockLogArray = [];
                $dataArray = [];
                $stidArray = [];
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
                             *  13	檢查碼 = 3~6碼x2 + 7~10碼 - 11~12碼x3 計算後的 個位數 
                             */
                            $N1 = ProductSupplier::find(request()->supid)->sup_number;
                            $N2 = request()->re_delivery;
                            $Y = substr($Deliverydate, 3,1);
                            $M = substr($Deliverydate, 5,2);
                            $D = substr($Deliverydate, 8,2);
                            $N3to7 = $Y.$M.$D;
                            $N8to9 = '00'; //不重複時的預設值
                            if($salesprice[$key] >= 1000){
                                $N10 = 1;
                                $N11to12 = substr($salesprice[$key], 0,3);
                            }else{
                                $N10 = rand(1,9);
                                $N10 = ($N10 === 1) ? 0 : $N10; //把1用0取代掉
                                /**
                                  前端送出儲存前必須確認salesprice的最後一碼為0
                                 */
                                $N11to12 = $salesprice[$key] / 10; 
                            }
                            /**
                             * 流水號$N8to9判斷與建立
                             */

                            $N1to12 = $N1.$N2.$N3to7.$N8to9.$N10.$N11to12;
                            $N13 = (int)substr($N1to12, 2,4) * 2 + (int)substr($N1to12, 6,4) - (int)substr($N1to12, 10,2) * 3;
                            
                            $insertProductIndexArray[] = [
                                'p_number'      => $N1to12.$N13,
                                'p_name'        => $value,
                                'p_retailprice' => $retailprice[$key],
                                'p_southprice'  => $southprice[$key],
                                'p_salesprice'  => $salesprice[$key],
                                'p_costprice'   => $costprice[$key],
                                'p_notes'       => $notes[$key],
                                'update_user'   => Admin::user()->id,
                                'last_delivery' =>  date('Y-m-d H:i:s'),
                                'created_at'    => date('Y-m-d H:i:s'),
                                'showsales'     => 1,
                            ];
                        }
                    }
            
                }
                /*****************************
                 * 
                 * 編輯 進貨單明細/庫存
                 * 
                 *****************************/
                elseif(request()->action == 'edit'){

                }
                $form->re_amount = $total;
                $form->update_user =  Admin::user()->id;
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
        return Admin::form(ProductReceipt::class, function (Form $form) {
            $form->select('supid', trans('admin::lang.product_supplier'))->options(
                ProductSupplier::all()->pluck('sup_name', 'supid')
            )->readOnly();

            $form->date('re_delivery', trans('admin::lang.re_delivery'))->readOnly();
            $form->textarea('re_notes', trans('admin::lang.notes'))->rows(2);

            $form->hidden('re_number');
            $form->hidden('re_amount');
            $form->hidden('update_user');

            //btn-append有另外寫js的append功能
            $form->button('btn-danger btn-append','+ 進貨商品')->on('click','ShowModal("product");');
        })->setWidth(7);
    }
}

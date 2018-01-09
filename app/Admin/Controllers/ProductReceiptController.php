<?php

namespace App\Admin\Controllers;

use App\ProductReceipt;
use App\ProductSupplier;
use App\ProductReceiptDetails;
use App\ProductIndex;
use App\Warehouse;

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
        $re_number = ProductReceipt::where('reid',$id)->pluck('re_number');
        $receiptdetails = ProductReceiptDetails::ofselected($re_number) ?: [];
        foreach($receiptdetails as $key => $value){
            $products[$key] = ProductIndex::where('pid',$value->pid)->get()->toArray()[0];
        }
        $rowTop = -30;
        $rowEvenOdd = ['even','odd'];
        $data = compact('products','rowTop','rowEvenOdd','receiptdetails');



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
        $receipt['re_user'] = Admin::user($receipt['re_user'])->name;

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
                return Admin::user($re_user)->name;
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
            $grid->actions(function ($actions) {
                $actions->setTitleField('re_number');
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
                    $max_number = ProductReceipt::all()
                    ->where('re_number', 'like', $Todaydate.$Supplier.'%')
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
                if(!empty(request()->pid)){
                    $red_amount = request()->red_amount;
                    $red_price = request()->red_price;
                    $red_quantity = request()->red_quantity;
                    $red_notes = request()->red_notes;
                    $dataArray = [];
                    $total = 0;
                    foreach(request()->pid as $key => $pid){
                        $dataArray[] = [
                            'pid'           => $pid,
                            're_number'     => $form->re_number,
                            'red_amount'    => $red_amount[$key],
                            'red_price'     => $red_price[$key],
                            'red_quantity'  => $red_quantity[$key],
                            'red_notes'     => $red_notes[$key],
                        ];
                        $total += $red_amount[$key];
                    }
                    if(!empty($dataArray))
                    {
                        $form->re_amount = $total;
                        ProductReceiptDetails::where('re_number',$form->re_number)->delete();
                        ProductReceiptDetails::insert($dataArray);
                    }

                }

                
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
            $form->hidden('reid');
        })->setWidth(7);
    }
}

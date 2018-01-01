<?php

namespace App\Admin\Controllers;

use App\ProductReceipt;
use App\ProductSupplier;

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
use Illuminate\Support\Facades\DB;

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
            });
            $grid->supid(trans('admin::lang.product_supplier'))->display(function ($supid) {
                $supplier = ProductSupplier::ofSupplier($supid);
                return $supplier->toArray()[0]['sup_name'];
            });
            $grid->re_number(trans('admin::lang.re_number'));
            $grid->re_user(trans('admin::lang.re_user'))->display(function ($re_user) {                
                return Admin::user($re_user)->name;
            });
            $grid->re_amount(trans('admin::lang.re_amount'))->display(function ($re_amount) {                
                return (int) $re_amount;
            });
            
            $grid->re_notes(trans('admin::lang.re_notes'));
                        

            //指定匯出Excel的資料庫欄位(不可使用關聯之資料庫欄位)
            $titles = ['supid', 'wid', 're_number', 're_user', 're_amount', 're_notes', 're_delivery'];

            $exporter = new ExcelExpoter();
            /**
             * setDetails()參數
             * 1：資料庫欄位 array
             * 2：匯出Excel檔案名 string
             * 3：Excel製作人名稱 string
             */
            $exporter->setDetails($titles,'商品資訊',Admin::user()->name);
            $grid->exporter($exporter);

            //不顯示匯入按鈕
            $grid->disableImport();
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
            $form->currency('re_amount', trans('admin::lang.re_amount'))->options(['digits' => 2]);
            $form->textarea('re_notes', trans('admin::lang.notes'))->rows(2);

            $form->hidden('wid')->default(Admin::user()->wid);
            $form->hidden('re_user')->default(Admin::user()->id);
            $form->hidden('re_number');

            //btn-append另外寫js的append功能(未完成)
            $form->button('btn-danger btn-append','+ 進貨商品')->on('click','ShowModal("product");');

            $form->saving(function(Form $form) {
                /**
                 * 進貨單編碼規則：日期YYMMDD(6)+廠商編號XX(2)+流水號(2)，共10碼
                 */
                if(!empty(request()->supid)){
                    $Todaydate = date('Ymd');
                    $Supplier = request()->supid;

                    //前補0至兩碼
                    $Supplier = str_pad($Supplier,2,"0",STR_PAD_LEFT);

                    //取得該日該廠商進貨單號的最大值
                    $max_number = DB::table('product_receipt')
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
            });
        })->setWidth(5);
    }
}

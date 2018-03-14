<?php

namespace App\Admin\Controllers;

use Encore\Admin\Auth\Database\Administrator;
use App\ProductIndex;
use App\ProductSeries;
use App\ProductCategory;
use App\Warehouse;
use App\Stock;
use App\StockLog;
use App\StockCategory;
use App\ProductSupplier;
use App\Admin\Extensions\ExcelExpoter;
use Maatwebsite\Excel\Facades\Excel;
use Encore\Admin\Widgets\Table;
use TCPDF;

use Encore\Admin\Auth\Permission;
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
use Illuminate\Http\Request;

class ProductIndexController extends Controller
{
    use ModelForm;
    protected $stock =[];

    /**
     * 回傳 已選的商品
     */
    public function selectedproduct(Request $request)
    {
        $products = $stock = $rowWidth = $rowLeft = $rowTitle = [];
        $selected = $request->selected ?: [];
        $action = $request->action;
        $target = $request->target;

        $tempProducts = ProductIndex::whereIn('pid',$selected)->get();
        
        //依照勾選順序排序
        foreach($tempProducts as $key => $product){
            $sortKey = array_search($product->pid,$selected);
            $products[$sortKey] = $product;
            $stock[$sortKey] = Stock::where('pid',$product->pid)->where('wid',Admin::user()->wid)->get()->toArray();
        }
        ksort($products);
        ksort($stock);

        $rowTop = empty($request->rowTop) ? -30 : (int)$request->rowTop ;
        $rowEvenOdd = ['even','odd'];
        $firsttime = filter_var($request->firsttime, FILTER_VALIDATE_BOOLEAN);
        $inputtext = filter_var($request->inputtext, FILTER_VALIDATE_BOOLEAN);

        //調貨單使用業務價
        if($target == 'hasstock'){
            $showPrice = 'p_salesprice';
            $detailid = 'tdid';
        }elseif($target == 'salesassign_hasstock'){
            $showPrice = 'p_salesprice';
            $detailid = 'sadid';
        }elseif($target == 'salescollect_hasstock'){
            $showPrice = 'p_salesprice';
            $detailid = 'scdid';
        }elseif($target == 'salesrefund_hasstock'){
            $showPrice = 'p_salesprice';
            $detailid = 'srdid';
        }else{
            $showPrice = 'p_costprice';
            $detailid = 'redid';
        }

        if($action == 'create'){
            $rowWidth = [33,180,150,50,60,80,80,80,90];
            $rowLeft = [0,33,213,363,413,473,553,633,713];
            $rowTitle = ['','商品編號','商品名','單位','庫存數',/*'款式',*/'數量','單價','總價','備註'];
        }elseif($action == 'edit'){

            if ($target == 'salescollect_hasstock' || $target == 'salesrefund_hasstock'){
                $action = 'editcheckadd';
                $checkProduct = 'scd_check';
                $rowWidth = [33,180,33,150,60,80,80,80,110];
                $rowLeft = [0,33,213,246,396,456,536,616,696];
                $rowTitle = ['','商品編號','點貨','商品名','單位',/*'款式',*/'數量','單價(業務)','總價','備註'];
            }else{
                $action = 'editadd';
                $rowWidth = [33,180,150,60,80,80,80,110];
                $rowLeft = [0,33,213,363,423,503,583,663];
                $rowTitle = ['','商品編號','商品名','單位',/*'款式',*/'數量','單價','總價','備註'];
            }
        }
        if ($target == 'salescollect_hasstock' || $target == 'salesrefund_hasstock'){
            $data = compact('action','inputtext','checkProduct','products','showPrice','detailid','rowWidth','rowLeft','rowTitle','rowTop','rowEvenOdd','firsttime','stock');
        }else{
            $data = compact('action','inputtext','products','showPrice','detailid','rowWidth','rowLeft','rowTitle','rowTop','rowEvenOdd','firsttime','stock');
        }
        return view('admin::productdetails', $data);
    }
    /**
     * 回傳 商品彈出視窗
     */
    public function modalsearch(Request $request)
    {
        $search = $request->search;
        $selected = $request->selected ?: [];
        if($search == 'searchall'){
            $products = ProductIndex::all()->sortByDesc('pid')->take(100);
        }else if($search == 'searchselected'){
            $products = ProductIndex::whereIn('pid',$selected)->get();
        }else{
            $products = ProductIndex::where('p_name','like','%'.$search.'%')->orWhere('p_number','like','%'.$search)->orWhere('p_number','like',$search.'%')->get();
        }
        $rowTop = -30;
        $rowEvenOdd = ['even','odd'];

        $data = compact('products','rowTop','rowEvenOdd','selected');
        return view('admin::result', $data);
    }
    /**
     * 回傳 商品彈出視窗
     */
    public function modalsearchstock(Request $request)
    {
        $search = $request->search;
        $selected = $request->selected ?: [];
        if($search == 'searchall'){

            $temp = Stock::where('wid', Admin::user()->wid)->where('st_stock','>',0)->pluck('pid');
            $products = ProductIndex::whereIn('pid',$temp)->get()->sortByDesc('pid')->take(100);

        }else if($search == 'searchselected'){
            $products = ProductIndex::whereIn('pid',$selected)->get();
        }else{

            $temp = Stock::where('wid', Admin::user()->wid)->where('st_stock', '>', 0)->pluck('pid');
            $products = ProductIndex::where(function ($query) use ($search) {
                $query->where('p_name', 'like', '%'.$search.'%')
                      ->orWhere('p_number', 'like', '%'.$search)
                      ->orWhere('p_number', 'like', $search.'%');
            })->whereIn('pid', $temp)->get();
        }
        $rowTop = -30;
        $rowEvenOdd = ['even','odd'];

        $data = compact('products','rowTop','rowEvenOdd','selected');
        return view('admin::result', $data);
    }
    /**
     * 回傳 商品搜尋Table
     */
    public function search(Request $request)
    {
        if($request->ajax()){
            $search = $request->ls_query;
            $url = config('admin.upload.host');
            $output = "";
            $products = ProductIndex::where('p_name','like','%'.$search.'%')->orWhere('p_number','like','%'.$search)->orWhere('p_number','like',$search.'%')->get();
            if($products){
                foreach ($products as $key => $product) {
                    $output .= '<tr>';
                    $column = htmlspecialchars($product->p_pic);
                    $output .= "<td><img src='{$url}/{$column}'></td>";
                    $column = htmlspecialchars($product->p_name);
                    $output .= "<td>{$column}</td>";
                    $output .= '</tr>';
                }
                return [
                    'status' => 'success',
                    'result' => json_encode([
                        'html' => $output,
                        'number_of_results' => count($products),
                        'total_pages'       => ceil(count($products)/5),
                    ])
                ];
            }
            // return Response($output);
        }

    }
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        Permission::check(['ProductIndex-Reader']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.product_index'));
            $content->description(trans('admin::lang.list'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.product_index')]
            );
            $content->row(function (Row $row) {
                /**
                 * 功能：搜尋商品，
                 * 可見欄位：商品名
                 */
                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('product'));
                    $form->method('GET');

                    /**
                     * !important Bug：搜尋功能和filter密不可分...
                     * 這裡要什麼欄位，filter就必須有那個欄位，才能正常搜尋
                     */
                    $form->text('p_number', trans('admin::lang.product_number'));
                    $form->text('p_name', trans('admin::lang.product_name'));

                    $form->disableSubmit();
                    $form->disableReset();
                    $form->enableSearch();

                    $column->append((new Box(trans('admin::lang.search'), $form))->style('success'));
                });
                /**
                 * 功能：快速新增，
                 * 可見欄位：商品名、業務價、成本價
                 * 隱藏欄位：最近更新者
                 */
                $row->column(6, function (Column $column) {

                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('product'));

                    $form->select('StockCategory', trans('admin::lang.stock_category'))->options(
                        StockCategory::all()->sortBy('sc_sort')->pluck('sc_name', 'sc_number')->transform(function ($item, $key) {
                            return $key.' - '.$item;
                        })
                    );
                    $form->select('ProductSupplier', trans('admin::lang.product_supplier'))->options(
                        ProductSupplier::all()->pluck('sup_name', 'sup_number')->transform(function ($item, $key) {
                            return $key.' - '.$item;
                        })
                    );
                    $form->hidden('p_number');
                    $form->hidden('inserttype')->default('quick');

                    $form->text('p_name', trans('admin::lang.product_name'))->rules('required');

                    $form->currency('p_retailprice', trans('admin::lang.p_retailprice'))->options(['digits' => 2]);
                    $form->currency('p_salesprice', trans('admin::lang.product_salesprice'))->options(['digits' => 2]);
                    if(Admin::user()->inRoles(['administrator', 'SuperWarehouse', 'Boss'])){
                        $form->currency('p_costprice', trans('admin::lang.product_costprice'))->options(['digits' => 2]);
                    }
                    $form->number('st_stock',trans('admin::lang.product_stock'))->default(0);

                    $form->hidden('update_user')->default(Admin::user()->id);

                    $column->append((new Box(trans('admin::lang.quicknew'), $form))->style('info'));
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

        Permission::check(['ProductIndex-Reader']);

        $product = ProductIndex::find($id)->toArray();

        //忽略不顯示的欄位
        $skipArray = ['pid','p_price','p_costprice'];
        //某些角色顯示欄位
        $showArray = [
            'p_costprice' => ['administrator','Boss','SuperWarehouse'],
        ];
        //顯示圖片欄位
        $imgArray = ['p_pic','p_images'];

        //置換商品分類的內容
        if(is_array($product['p_category'])){
            $tempArray = [];
            foreach($product['p_category'] as $value){
                $tempArray[] = ProductCategory::find($value)->pc_name;
            }
            $product['p_category'] = $tempArray;
        }else if(!empty($product['p_category'])){
            $product['p_category'] = ProductCategory::find($product['p_category'])->pc_name;
        }

        //置換主題系列的內容
        if(is_array($product['p_series'])){
            $tempArray = [];
            foreach($product['p_series'] as $value){
                $tempArray[] = ProductSeries::find($value)->ps_name;
            }
            $product['p_series'] = $tempArray;
        }else if(!empty($product['p_series'])){
            $product['p_series'] = ProductSeries::find($product['p_series'])->ps_name;
        }

        //置換最近更新者的內容
        $product['update_user'] = Administrator::find($product['update_user'])->name;

        $header[] = '商品資訊';
        foreach($product as $key => $value){

            if(in_array($key,$skipArray) || empty($value)){
                if(!(isset($showArray[$key]) && Admin::user()->inRoles($showArray[$key]))){
                    continue;
                }
            }

            //欄位中文化
            $newkey = trans('admin::lang.'.$key);

            /**
             * 內容排版
             *    ┬ 圖片 ┬ 主圖(string)
             *    │      └ 副圖(array)---連續印出
             *    │
             *    └ 文字 ┬ 分類、系列(array)---用/分隔
             *           └ 其他(string)
             */


            if(in_array($key,$imgArray)){
                if(is_array($value)){
                    $content = '';
                    foreach($value as $temp){
                        $content .= '<img src="' .rtrim(config('admin.upload.host'), '/').'/'. $temp . '" width="50px" />';
                    }
                    $rows[$newkey] = $content;
                }else{
                    $rows[$newkey] = '<img src="' .rtrim(config('admin.upload.host'), '/').'/'. $value . '" width="100px" />';
                }

            }else{
                if(is_array($value)){
                    $content = '';
                    foreach($value as $temp){
                        if(empty($content))
                            $content = $temp;
                        else
                            $content .= ' / ' . $temp;
                    }
                    $rows[$newkey] = $content;
                }else{
                    $rows[$newkey] = nl2br($value);
                }
            }


        }

        $table = new Table($header, $rows);
        $table->class('table table-hover');
        return $table->render();
    }
    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        Permission::check(['ProductIndex-Editor']);
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('admin::lang.product_index'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.product_index'), 'url' => '/product'],
                ['text' => trans('admin::lang.edit')]
            );
            $content->body($this->form($id)->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {

        Permission::check(['ProductIndex-Creator']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.product_index'));
            $content->description(trans('admin::lang.create'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.product_index'), 'url' => '/product'],
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
        return Admin::grid(ProductIndex::class, function (Grid $grid) {

            /**
             * 顯示序號(Bug：只能顯示當頁從1開始的序號，第二頁依然從1開始)
             * https://github.com/z-song/laravel-admin/issues/1374
             */
            // $grid->number('No');
            // $grid->rows(function ($row, $number) {
            //     $row->column('number', $number+1);
            // });

            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->like('p_name','商品名');
                $filter->like('p_number','商品編號');
            });
            $grid->pid('ID')->sortable();
            $grid->p_number(trans('admin::lang.product_number'))->display(function ($p_number) {
                return '<a href="product/'.$this->pid.'/qrcode" target="_blank">'.$p_number.'</a>';
            })->sortable();
            $grid->p_name(trans('admin::lang.name'));
            $grid->p_pic(trans('admin::lang.product_pic'))->display(function ($p_pic) {
                return "<img src='".rtrim(config('admin.upload.host'), '/').'/'.$p_pic."' style='max-width:150px;max-height:100px;' onerror='this.src=\"/images/404.jpg\"'/>";
            });//->image('',150,100);
            $grid->p_salesprice(trans('admin::lang.product_salesprice'));
            if(Admin::user()->inRoles(['administrator', 'SuperWarehouse', 'Boss'])){
                $grid->p_costprice(trans('admin::lang.product_costprice'));
            }

            if(Admin::user()->isAdministrator()){

                /**
                 * 超級管理員可以看所有庫存
                 * 倉庫關聯目前只有寫到stock1~stock4，
                 * 如果再增加倉庫要到Model去增加function
                 * 這BUG屬於模組本身架構沒有考慮到這部分，要修正的話工程太浩大，所以就這樣吧。
                 * (function stock留著，edit頁是用它...)
                 */
                $warehouse = Warehouse::all()->pluck('name', 'id')->toArray();
                foreach($warehouse as $wid => $w_name){
                    $grid->{'stock'.$wid}($w_name)->where('wid',$wid)->sum('st_stock')->value(function ($stock) {
                        if(!empty($stock)){
                            return $stock;
                        }
                        return '';
                        // return "<span class='label label-warning'>未填寫庫存</span>";
                    });
                }

            }else{
                //非超級管理員只能看到自己倉庫的庫存 + 台中倉倉庫庫存
                $grid->stock(trans('admin::lang.product_stock'))->where('wid', Admin::user()->wid)->sum('st_stock')->value(function ($stock) {
                    if(!empty($stock)){
                        return $stock;
                    }
                    return '';
                    // return "<span class='label label-warning'>無庫存資料</span>";
                });
                $grid->stock2('台中庫存')->where('wid','2')->sum('st_stock')->value(function ($stock) {
                    if(!empty($stock)){
                        return $stock;
                    }
                    return '';
                });
            }

            //指定匯出Excel的資料庫欄位(不可使用關聯之資料庫欄位)
            $titles = ['p_name', 'p_number', 'showfront', 'p_salesprice', 'p_costprice'];

            $exporter = new ExcelExpoter();
            /**
             * setDetails()參數
             * 1：資料庫欄位 array
             * 2：匯出Excel檔案名 string
             * 3：Excel製作人名稱 string
             */
            $exporter->setDetails($titles,'商品資訊',Admin::user()->name);
            $grid->exporter($exporter);

            //顯示匯入按鈕
            $grid->allowImport();

            //眼睛彈出視窗的Title，請設定資料庫欄位名稱
            $grid->actions(function ($actions) {
                $actions->setTitleField(['p_name']);
            });
            $states = [
                'on'  => ['value' => 1, 'text' => 'YES', 'color' => 'success'],
                'off' => ['value' => 2, 'text' => 'No', 'color' => 'danger'],
            ];
            $grid->showfront(trans('admin::lang.showfront'))->status()->switch($states);
            // $grid->showsales(trans('admin::lang.showsales'))->status()->switch($states);

            // $grid->showfront('前台顯示')->value(function ($showfront) {
            //     return $showfront ? "<span class='label label-success'>Yes</span>" : "<span class='label label-danger'>No</span>";
            // });
            // $grid->showsales('業務顯示')->value(function ($showsales) {
            //     return $showsales ? "<span class='label label-success'>Yes</span>" : "<span class='label label-danger'>No</span>";
            // });
            $grid->updated_at(trans('admin::lang.updated_at'));
            $grid->model()->orderBy('pid', 'desc');
            $grid->define_preg(url('/admin/product'),'返回');
        });
    }

    /**
     * Make a form builder.
     * 創建時使用的form表格
     * @return Form
     */
    protected function form($id = null)
    {
        return Admin::form(ProductIndex::class, function (Form $form) use ($id){

            $form->tab('商品資訊', function ($form) use ($id){
                if(!$id){ //創建
                    $form->select('StockCategory', trans('admin::lang.stock_category'))->options(
                        StockCategory::all()->sortBy('sc_sort')->pluck('sc_name', 'sc_number')->transform(function ($item, $key) {
                            return $key.' - '.$item;
                        })
                    )->rules('required');;
                    $form->select('ProductSupplier', trans('admin::lang.product_supplier'))->options(
                        ProductSupplier::all()->pluck('sup_name', 'sup_number')->transform(function ($item, $key) {
                            return $key.' - '.$item;
                        })
                    )->rules('required');;
                    $form->hidden('p_number');
                }else{ //編輯
                    $form->text('p_number', trans('admin::lang.product_number'))->rules('required');
                }
                $form->text('p_name', trans('admin::lang.product_name'))->rules('required');

                $form->multipleSelect('p_category', trans('admin::lang.product_category'))->options(
                    ProductCategory::all()->pluck('pc_name', 'pcid')
                );
                $form->checkbox('p_series', trans('admin::lang.product_series'))->options(
                    ProductSeries::all()->pluck('ps_name', 'psid')
                );
                $form->image('p_pic', trans('admin::lang.product_pic'))->uniqueName()->move('product');
                $form->multipleImage('p_images', trans('admin::lang.product_images'));
                $form->textarea('p_description', trans('admin::lang.description'))->rows(5);
                $states = [
                    'on'  => ['value' => 1, 'text' => '顯示', 'color' => 'success'],
                    'off' => ['value' => 0, 'text' => '隱藏', 'color' => 'danger'],
                ];
                $form->switch('showfront', trans('admin::lang.showfront'))->states($states)->default(1);
                $form->switch('shownew', trans('admin::lang.shownew'))->states($states)->default(1);
                $form->text('p_unit', trans('admin::lang.p_unit'))->default('個')->setWidth(1);
                $form->hidden('update_user')->default(Admin::user()->id);

            });
            $form->tab('價格/業務', function ($form) use ($id){

                $form->currency('p_retailprice', trans('admin::lang.p_retailprice'))->options(['digits' => 2]);
                $form->currency('p_salesprice', trans('admin::lang.product_salesprice'))->options(['digits' => 0])->rules('required');
                if(Admin::user()->inRoles(['administrator', 'SuperWarehouse', 'Boss'])){
                    $form->currency('p_costprice', trans('admin::lang.product_costprice'))->options(['digits' => 2]);
                }                

                $states = [
                    'on'  => ['value' => 1, 'text' => '顯示', 'color' => 'success'],
                    'off' => ['value' => 0, 'text' => '隱藏', 'color' => 'danger'],
                ];

                // $form->switch('showsales', trans('admin::lang.showsales'))->states($states)->default(1);
                $form->textarea('p_notes', trans('admin::lang.salesman').trans('admin::lang.notes'))->rows(5);

            });
            $form->tab('庫存', function ($form) use ($id){
                /**
                 * BUG：Model中的hasMany後面接where查詢，顯示OK，但儲存時不OK，資料不乖乖依序存檔，改第三個但蓋掉了第一個庫存...
                 * 找不到是哪裡出問題，只能說又是一個模板本身的BUG...QQ 已在gitHub上發問，如果之後有神人解答的話再來改
                 *
                 * 目前每個管理者(有商品修改權限的)都可以看到所有倉庫的庫存資料跟領貨限制........
                 */
                // $relationFunction = ( Admin::user()->isAdministrator() || Admin::user()->isRole('SuperWarehouse')) ? 'stock1' : 'stock';
                $relationFunction = 'stock1';
                $form->hasMany($relationFunction,'庫存', function (Form\NestedForm $form) use ($id){
                    if(!$id){ //創建
                        if(Admin::user()->isAdministrator()){
                            //超級管理員可以自行選擇倉庫
                            $form->select('wid', trans('admin::lang.warehouse'))->options(
                                Warehouse::all()->pluck('name', 'id')
                            );
                        }else{
                            //非超級管理員使用本身綁定的倉庫id
                            $form->hidden('wid')->default(Admin::user()->wid);
                        }
                    }else{ //編輯
                        if (Admin::user()->isAdministrator() || Admin::user()->isRole('SuperWarehouse')) {
                            //超級管理員 或 總倉倉管 可見所有倉庫庫存
                            $form->select('wid', trans('admin::lang.warehouse'))->options(
                                Warehouse::all()->pluck('name', 'id')
                            )->readOnly();
                        } else {
                            //使用本身綁定的倉庫id
                            $form->hidden('wid')->default(Admin::user()->wid);
                        }
                    }

                    // $form->text('st_type',trans('admin::lang.product_type'))->default('不分款');
                    $form->text('st_notes',trans('admin::lang.notes'));
                    if(!$id){ //編輯
                        $form->display('st_stock', trans('admin::lang.product_stock'))->default(0);
                    }
                    // $form->number('st_stock',trans('admin::lang.product_stock'))->default(0);
                    $form->select('st_unit',trans('admin::lang.sales_unit'))->options(
                        ['每人','每間']
                    );
                    $form->number('st_collect',trans('admin::lang.product_sales'))->default(0);
                    $form->hidden('update_user')->default(Admin::user()->id);
                });
            });

            $form->saving(function(Form $form) use ($id) {


                if(!$id && !empty(request()->StockCategory) && !empty(request()->ProductSupplier)){

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
                    $N1 = request()->ProductSupplier;
                    $N2 = request()->StockCategory;
                    $Deliverydate = date('Y-m-d');
                    $Y = substr($Deliverydate, 3, 1);
                    $M = substr($Deliverydate, 5, 2);
                    $D = substr($Deliverydate, 8, 2);
                    $N3to7 = $Y.$M.$D;
                    $N8to9 = '00'; //不重複時的預設值
                    if ($form->p_salesprice >= 1000) {
                        $N10 = 1;
                        $N11to12 = substr($form->p_salesprice, 1, 2);
                    } else {
                        $N10 = rand(1, 9);
                        $N10 = ($N10 === 1) ? 0 : $N10; //把1用0取代掉
                        $N11to12 = floor($form->p_salesprice / 10);
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

                    //填充到p_number欄位中
                    $form->p_number = $N1to12.$N13;

                }
            });
            $form->saved(function(Form $form) use ($id) {
                // $stock = request()->stock;
                // foreach ($stock as $key => $val) {
                //     $updatestockArray = [
                //         'pid'           =>  $id,
                //         'wid'           =>  $val['wid'],
                //         'st_type'       =>  $val['st_type'],
                //         'st_barcode'    =>  $val['st_barcode'],
                //         'st_notes'      =>  $val['st_notes'],
                //         'st_unit'       =>  $val['st_unit'],
                //         'st_collect '   =>  $val['st_collect'],
                //         'update_user'   =>  Admin::user()->id,
                //         'created_at'    =>  date('Y-m-d H:i:s'),
                //         'updated_at'    =>  date('Y-m-d H:i:s'),
                //     ];
                //     if($val['stid']){
                //         Stock::find($val['stid'])->update($updatestockArray);
                //     }else{
                //         Stock::insert($updatestockArray);
                //     }
                // }

                if(!$id && !empty(request()->StockCategory)&&!empty(request()->ProductSupplier) && !empty(request()->inserttype)){

                    $insertstock = [
                        'pid'           =>  $form->model()->pid,
                        'wid'           =>  Admin::user()->wid,
                        // 'st_type'       =>  '不分款',
                        'st_stock'      =>  $form->st_stock ?: 0,
                        'update_user'   =>  Admin::user()->id,
                        'created_at'    =>  date('Y-m-d H:i:s'),
                        'updated_at'    =>  date('Y-m-d H:i:s'),
                    ];
                    Stock::insert($insertstock);
                }
            });
            $form->ignore(['StockCategory','ProductSupplier']);
        });
    }
    /**
     * import a file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        if($request->file('imported-file')){
            $path = $request->file('imported-file')->getRealPath();
            $data = Excel::load($path, function($reader){
            })->get();

            if(!empty($data) && $data->count())
            {                
                $i = 1;
                foreach ($data->toArray() as $row){
                    if(!empty($row) && !empty($row['p_name'])){

                        $N1to2 = '00';
                        $YnM = substr(date('Ym'),-3,3);
                        $N6to9 = str_pad($i, 4, "0", STR_PAD_LEFT);
                        $i++;
                        if ($row['p_salesprice'] >= 1000) {
                            $N10 = 1;
                            $N11to12 = substr($row['p_salesprice'], 1, 2);
                        } else {
                            $N10 = rand(1, 9);
                            $N10 = ($N10 === 1) ? 0 : $N10; //把1用0取代掉
                            $N11to12 = floor($row['p_salesprice'] / 10);
                        }
                        
                        //前補0至兩碼
                        $N11to12 = str_pad($N11to12, 2, "0", STR_PAD_LEFT);

                        $N1to12 = $N1to2.$YnM.$N6to9.$N10.$N11to12;
                        $N13 = (int)substr($N1to12, 2, 4) * 2 + (int)substr($N1to12, 6, 4) - (int)substr($N1to12, 10, 2) * 3 - (int) substr($N1to12, 8, 1) * 7;
                        //取個位數
                        $N13 = substr($N13, -1);


                        $dataArray1 = [
                            'p_number' => $N1to12.$N13,
                            'p_name' => $row['p_name'],
                            // 'p_retailprice' => $row['p_retailprice'],
                            'p_salesprice' => $row['p_salesprice'],
                            'p_costprice' => $row['p_costprice'],
                            'update_user' => Admin::user()->id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                        if(!empty($dataArray1))
                        {
                            $pid = ProductIndex::insertGetId($dataArray1,'pid');

                            //有庫存才增加庫存資料
                            // if((int)$row['st_stock'] > 0){
                                $dataArray2 = [
                                'pid'           =>  $pid,
                                'wid'           =>  Admin::user()->wid,
                                // 'st_type'       =>  '不分款',
                                'st_stock'      =>  $row['st_stock'],
                                'update_user'   =>  Admin::user()->id,
                                'created_at'    =>  date('Y-m-d H:i:s'),
                                'updated_at'     =>  date('Y-m-d H:i:s'),
                                ];
                                $stid = Stock::insertGetId($dataArray2,'stid');

                                $insertStockLogArray[] = [
                                    'pid'          =>  $pid,
                                    'wid'          =>  Admin::user()->wid,
                                    'stid'         =>  $stid,
                                    'sl_calc'      =>  '+',
                                    'sl_quantity'  =>  $row['st_stock'],
                                    'sl_stock'     =>  $row['st_stock'],
                                    'sl_notes'     =>  '商品匯入',
                                    'update_user'  =>  Admin::user()->id,
                                    'updated_at'    =>  date('Y-m-d H:i:s'),
                                ];
                            // }
                        }
                    }
                }
                if(!empty($insertStockLogArray))
                {
                    StockLog::insert($insertStockLogArray);
                }
            }
        }
        return back();
    }
    protected function qrcode($id){
        $pdf = new TCPDF();

        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->AddPage('L', 'A4');
        $pdf->SetFont('msjh', '', 15);
        $style = array(
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $products = ProductIndex::where('pid',$id)->select('p_name','p_number')->first();
        if(empty($products)){
            $error = new MessageBag(['title'=>'提示','message'=>'找不到此商品編號!']);
            return back()->withInput()->with(compact('error'));
        }
        $QRCode_content = $products->p_number;
        $QRCode_title = $products->p_name;
        $pdf->Text(10, 5, $QRCode_title);
        $pdf->SetFont('msjh', '', 8);
        $QRCode_size = 20;
        $QRCode_width = 35;
        $QRCode_height = 30;
        $y = 15;
        for($i=1;$i<=6;$i++){
            $x = 15;
            for ($j=1;$j<=8;$j++) {
                $pdf->write2DBarcode($QRCode_content, 'QRCODE,H', $x, $y, $QRCode_size, $QRCode_size, $style, 'N');
                $pdf->Text($x-1.5, $y+20, $QRCode_content);
                $x = $x + $QRCode_width;
            }
            $y = $y + $QRCode_height;
        }

        $pdf->Output($QRCode_content.'.pdf', 'I');
    }
}

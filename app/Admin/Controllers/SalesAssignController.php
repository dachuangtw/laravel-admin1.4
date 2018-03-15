<?php

namespace App\Admin\Controllers;

use Encore\Admin\Auth\Database\Administrator;
use App\SalesAssign;
use App\SalesAssignDetails;
use App\Warehouse;
use App\ProductIndex;
use App\Stock;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Auth\Permission;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Widgets\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Request;
use App\Admin\Extensions\Tools\DateChooser;
use App\Admin\Extensions\ExcelExpoter;
 /**
 * 業務每日配貨
 * 尚未完成:
 *      1.當業務開始領貨鎖定當日配貨(無法編輯/刪除)
 *      2.判斷倉庫商品庫存是否足夠業務配貨
 */
class SalesAssignController extends Controller
{
    use ModelForm;
    /**
     * 編輯時，回傳配貨單明細
     */
    public function salesassigndetails($id)
    {
        $stock = $rowWidth = $rowLeft = $rowTitle = [];
        $action = 'edit';
        $detailid = 'sadid';

        $firsttime = true;
        $inputtext = true;

        $allReadonly = '';
        $SalesAssign = SalesAssign::find($id);
        $savedDetails = SalesAssignDetails::ofselected($SalesAssign->assign_id) ?: [];
        foreach($savedDetails as $key => $value){
            $products[$key] = ProductIndex::find($value->pid);
            // $stock[$value->stid] = Stock::find($value->stid)->st_type;
        }
        // $rowWidth = [33,100,150,60,80,80,80,80,110];
        // $rowLeft = [0,33,133,283,343,423,503,583,663];
        $rowWidth = [33,180,150,60,80,80,80,110];
        $rowLeft = [0,33,213,363,423,503,583,663];
        $rowTitle = ['','商品編號','商品名','單位',/*'款式',*/'配貨數','單價(業務)','總價','備註'];
        $showPrice = 'sad_salesprice';
        $showQuantity = 'sad_quantity';
        $showAmount = 'sad_amount';
        $showNotes = 'sad_notes';
        $rowTop = -30;
        $rowEvenOdd = ['even','odd'];
        $data = compact('action','detailid','products','showPrice','showQuantity','showAmount','showNotes','rowWidth','rowLeft','rowTitle','rowTop','rowEvenOdd','firsttime','inputtext','allReadonly','savedDetails','stock');

        return view('admin::productdetails', $data);
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        Permission::check(['SalesAssign-Reader']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.sales_assign'));
            $content->description(trans('admin::lang.list'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_assign')]
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
        Permission::check(['SalesAssign-Reader']);
        $salesassign = SalesAssign::find($id)->toArray();

        //忽略不顯示的欄位
        $skipArray = ['said','created_at','updated_at','deleted_at'];
        //顯示圖片欄位
        $imgArray = [];

        $salesassign['wid'] = Warehouse::find($salesassign['wid'])->name;
        $salesassign['update_user'] = Administrator::find($salesassign['update_user'])->name;

        $header[] = '配貨單資訊';
        foreach($salesassign as $key => $value){

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
        // $SalesAssign = SalesAssign::find($id)->assign_id;
        $savedDetails = SalesAssignDetails::ofselected($salesassign['assign_id']) ?: [];
        foreach($savedDetails as $key => $value){
            $products[$key] = ProductIndex::find($value->pid);
            // $stock[$key] = Stock::find($value->stid)->st_type;
        }
        $action = 'view';
        $detailid = 'sadid';
        $firsttime = true;
        $inputtext = false;

        // $rowWidth = [33,100,150,60,80,80,80,80,110];
        // $rowLeft = [0,33,133,283,343,423,503,583,663];
        $rowWidth = [33,180,150,60,80,80,80,110];
        $rowLeft = [0,33,213,363,423,503,583,663];
        $rowTitle = ['','商品編號','商品名','單位',/*'款式',*/'數量','單價','總價','備註'];
        $showPrice = 'sad_salesprice';
        $showQuantity = 'sad_quantity';
        $showAmount = 'sad_amount';
        $showNotes = 'sad_notes';
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
        Permission::check(['SalesAssign-Editor']);
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('admin::lang.sales_assign'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_assign'), 'url' => '/sales/assign'],
                ['text' => trans('admin::lang.edit')]
            );
            //判斷配貨倉庫，否則無權訪問編輯，超級管理員權限all(暫定)
            $check_wid = SalesAssign::find($id)->wid;
            if($check_wid == Admin::user()->wid || Admin::user()->isAdministrator()){
                $content->body($this->form()->edit($id));
                $script = <<<SCRIPT
                ShowSalesAssignDetails('$id');
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
        Permission::check(['SalesAssign-Creator']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.sales_assign'));
            $content->description(trans('admin::lang.create'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_assign'), 'url' => '/sales/assign'],
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
        Permission::check(['SalesAssign-Reader']);
        return Admin::grid(SalesAssign::class, function (Grid $grid) {

            $grid->model()->orderBy('assign_date', 'desc'); // 預設排序
            $grid->filter(function($filter){
                $filter->disableIdFilter();
                if(Admin::user()->isAdministrator()){
                    $filter->where(function ($query) {
                        $query->where('wid',  "{$this->input}");
                    }, trans('admin::lang.warehouse'))->select(
                        Warehouse::orderBy('sort')->pluck('name', 'id')->toArray()
                    );
                }
                $filter->like('assign_id',trans('admin::lang.assign_id'));
                $filter->where(function ($query) {
                    $query->where(\DB::raw("date_format(assign_date, '%Y-%m-%d')"), '>=', "{$this->input}");
                }, '配貨日期(起)')->date();
                $filter->where(function ($query) {
                    $query->where(\DB::raw("date_format(assign_date, '%Y-%m-%d')"), '<=', "{$this->input}");
                }, '配貨日期(迄)')->date();
                // $filter->between('assign_date', trans('admin::lang.assign_date'))->date();
            });
            $grid->actions(function ($actions) {

                $actions->setTitleExtra('配貨單號：'); // 自訂，標題前面提示
                $actions->setTitleField(['assign_id']);
                // 没有權限角色不顯示按鈕
                if (!Admin::user()->can('SalesAssign-Deleter')) {
                    $actions->disableDelete();
                }
                if (!Admin::user()->can('SalesAssign-Editor')) {
                    $actions->disableEdit();
                }
            });
            //全部/本月/上月按鈕，預設為本月
            $grid->tools(function ($tools) {
                $tools->append(new DateChooser());
            });

            switch (Request::get('assign_date'))
            {
                case 'lastmonth':
                    $lastmonth = array(date('Y-m-01', strtotime('-1 month')),date('Y-m-t', strtotime('-1 month')));
                    $grid->model()->whereBetween('assign_date', $lastmonth);
                    break;
                case 'thismonth':
                    $thismonth = array(date('Y-m-01'),date('Ymt'));
                    $grid->model()->whereBetween('assign_date', $thismonth);
                    break;
            }

            $grid->number('No.');
            $grid->rows(function ($row, $number) {
                $row->column('number', $number+1);
            });
            $grid->assign_date(trans('admin::lang.assign_date'))->sortable();
            $grid->assign_id(trans('admin::lang.assign_id'))->sortable();

            //判斷是否為超級管理員，則只可看所屬倉庫內容
            if(!Admin::user()->isAdministrator()){
                $grid->model()->where('wid',Admin::user()->wid);
            }else{
                $grid->wid(trans('admin::lang.warehouse'))->sortable()->display(function($wid) {
                    return Warehouse::find($wid)->name;
                })->label('info');
            }

            $grid->assign_amount(trans('admin::lang.assign_amount'))->sortable();
            $grid->update_user(trans('admin::lang.update_user'))->display(function($userId) {
                   return Administrator::find($userId)->name;
            });

            //$grid->created_at(trans('admin::lang.created_at'));
            $grid->updated_at(trans('admin::lang.updated_at'));

            //excel 匯出設定
            //指定匯出Excel的資料庫欄位(不可使用關聯之資料庫欄位)
            $titles = ['assign_date','assign_id','wid','assign_amount'];

            $exporter = new ExcelExpoter();
            /**
             * setDetails()參數
             * 1：資料庫欄位 array
             * 2：匯出Excel檔案名 string
             * 3：Excel製作人名稱 string
             */
            /**
             * setForeignKeys($foreignKeys)外部鍵設定
             */
            $foreignKeys = [
                'wid'  =>  [
                    'dbname' =>  'warehouse',
                    'id' =>  'wid',
                    'target' =>  'w_name',
                ],
            ];
            $exporter->setForeignKeys($foreignKeys);
            $exporter->setDetails($titles,'每日配貨表'.date('Ymd'),Admin::user()->name);
            $grid->exporter($exporter);
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SalesAssign::class, function (Form $form) {

            //判斷是否為編輯狀態
            if(strpos(url()->current(), '/edit') !== false) {
                $form->date('assign_date',trans('admin::lang.assign_date'))->readOnly();
                $form->text('assign_id',trans('admin::lang.assign_id'))->readOnly();
                if(Admin::user()->isAdministrator()){
                    $form->select('wid',trans('admin::lang.wid'))
                        ->options(Warehouse::all()->pluck('name','id'))->readOnly();
                }else{
                    $form->hidden('wid',trans('admin::lang.wid'))->value(Admin::user()->wid);
                }
            }else{
                $form->date('assign_date',trans('admin::lang.assign_date'))->defaultdate('YYYY-MM-DD');
                $form->html('<font color="#333">系統自動產生</font>',trans('admin::lang.assign_id'));
                //判斷超級使用者
                if(Admin::user()->isAdministrator()){
                    $form->select('wid',trans('admin::lang.wid'))
                        ->options(Warehouse::orderBy('sort')->pluck('name','id'))->rules('required');
                }else{
                    $form->hidden('wid',trans('admin::lang.wid'))->value(Admin::user()->wid);
                }
            }
            $form->hidden('assign_id',trans('admin::lang.assign_id'));
            $form->textarea('assign_notes',trans('admin::lang.notes'))->rows(2);
            $form->hidden('update_user')->value(Admin::user()->id);
            $form->hidden('assign_amount');
            $form->divide();

            //btn-append有另外寫js的append功能
            //ShowModal("salesassign_hasstock"):查詢所屬倉庫商品庫存
            $form->button('btn-danger btn-append','+ 配貨商品')->on('click','ShowModal("salesassign_hasstock");');

            $form->saving(function (Form $form) {

                if(empty(request()->pid)){
                    $error = new MessageBag(['title'=>'提示','message'=>'未填寫配貨商品!']);
                    return back()->withInput()->with(compact('error'));
                }
                if (empty(request()->assign_id)){
                    // 補0到第三位，每日配貨單號編碼:所選擇配貨日期+倉庫代號，共10碼 ex:20180126002
                    $wid_value = str_pad($form->wid,3,'0',STR_PAD_LEFT);
                    $form->assign_id = str_replace('-','',dump($form->assign_date)).$wid_value;
                }else{
                    $form->ignore(['assign_id']);
                }
                if($form->assign_id !== $form->model()->assign_id && SalesAssign::where('assign_id',$form->assign_id)->value('said')){
                    $error = new MessageBag(['title'=>'提示','message'=>'此配貨單已存在!']);
                    return back()->withInput()->with(compact('error'));
                }

                $sad_amount = request()->amount;
                $sad_salesprice = request()->price;
                $sad_quantity = request()->quantity;
                $sad_notes = request()->notes;
                $stid = request()->stid;
                $sadid = request()->sadid; //配貨明細id
                $insertProductLogArray = [];
                $insertStockLogArray = [];
                $dataArray = [];
                $stidArray = [];
                $total = 0;
                 /**
                 * 新增 配貨單明細
                 */
                if(request()->action == 'create'){
                    foreach(request()->pid as $key => $pid){
                        $dataArray[] = [
                            'pid'           =>  $pid, //商品編號
                            'assign_id'     =>  $form->assign_id, //配貨單號
                            'stid'          =>  $stid[$key], //庫存id
                            'sad_amount'    =>  $sad_amount[$key], //總金額
                            'sad_salesprice'=>  $sad_salesprice[$key], //業務單價
                            'sad_quantity'  =>  $sad_quantity[$key], //數量
                            'sad_notes'     =>  $sad_notes[$key], //備註
                        ];
                        $stidArray[] = $stid[$key];
                        $total += $sad_amount[$key];
                    }
                    // SalesAssignDetails::where('assign_id',$form->assign_id)->delete();
                    SalesAssignDetails::insert($dataArray);
                }
                /*****************************
                 *
                 * 編輯 配貨單明細
                 *
                 *****************************/
                elseif(request()->action == 'edit'){
                    //原本的配貨明細(數量/明細id)
                    $retailSadid = SalesAssignDetails::where('assign_id',$form->assign_id)->pluck('sad_quantity','sadid')->toArray();

                    //欲刪除的配貨明細 - 使用unset($deleteSadid[$sadid])移除沒有要刪除的配貨明細
                    $deleteSadid = array_keys($retailSadid);

                    //配貨明細更新:新增/修改/刪除
                    foreach(request()->pid as $key => $pid){

                        $insertSalesAssignArray[] = [
                            'pid'           =>  $pid,
                            'assign_id'     =>  $form->assign_id,
                            'stid'          =>  $stid[$key],
                            'sad_amount'    =>  $sad_amount[$key],
                            'sad_salesprice'=>  $sad_salesprice[$key],
                            'sad_quantity'  =>  $sad_quantity[$key],
                            'sad_notes'     =>  $sad_notes[$key],
                        ];
                        // SalesAssignDetails::updateorcreate($insertSalesAssignArray);
                        $stidArray[] = $stid[$key];
                        $total += $sad_amount[$key];

                        if (isset($sadid[$key])) {
                            SalesAssignDetails::updateOrCreate(['sadid' => $deleteSadid[$key]], $insertSalesAssignArray[$key]);
                            $unsetKey = array_search($sadid[$key],$deleteSadid);
                            unset($deleteSadid[$unsetKey]);
                        }elseif(empty($sadid[$key])){
                            SalesAssignDetails::create($insertSalesAssignArray[$key]);
                        }
                    }
                    SalesAssignDetails::whereIn('sadid',$deleteSadid)->delete();
                }
                $form->assign_amount = $total;
                $form->update_user = Admin::user()->id;
            });
        });
    }
}

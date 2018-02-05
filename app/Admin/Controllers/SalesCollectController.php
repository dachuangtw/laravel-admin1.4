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
use Encore\Admin\Widgets\Box;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class SalesCollectController extends Controller
{
    use ModelForm;
    /**
     * 編輯時，回傳配貨單明細
     */
    public function salescollectdetails($id)
    {
        $stock = $rowWidth = $rowLeft = $rowTitle = [];
        $action = 'edit';
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
        $rowWidth = [33,100,150,60,80,80,80,80,110];
        $rowLeft = [0,33,133,283,343,423,503,583,663];
        $rowTitle = ['','商品編號','商品名','單位','款式','配貨數','單價(業務)','總價','備註'];
        $showPrice = 'scd_salesprice';
        $showQuantity = 'scd_quantity';
        $showAmount = 'scd_amount';
        $showNotes = 'scd_notes';
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
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('admin::lang.sales_collect'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_collect'), 'url' => '/sales/collect'],
                ['text' => trans('admin::lang.edit')]
            );

            // $content->body($this->form()->edit($id));
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
        return Admin::grid(SalesCollect::class, function (Grid $grid) {

            $grid->model()->orderBy('collect_date', 'desc'); // 預設排序
            $grid->filter(function($filter){
                $filter->disableIdFilter();
                if(Admin::user()->isAdministrator()){
                    $filter->where(function ($query) {
                        $query->where('wid',  "{$this->input}");
                    }, trans('admin::lang.warehouse'))->select(
                        Warehouse::all()->pluck('w_name', 'wid')->toArray()
                    );
                }  
                $filter->like('collect_id',trans('admin::lang.collect_id'));
                $filter->between('collect_date', trans('admin::lang.collect_date'))->date();
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
            $grid->sales_id(trans('admin::lang.salesname'))->display(function($sales_id) {
                    return Sales::find($sales_id)->name;
            });
            $grid->collect_amount(trans('admin::lang.collect_amount'))->sortable();
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

            //判斷為編輯狀態
            if(strpos(url()->current(), '/edit') !== false) {
                $form->date('collect_date',trans('admin::lang.collect_date'))->readOnly();
                $form->text('collect_id',trans('admin::lang.collect_id'))->readOnly();
                switch (Admin::user()) {
                    case 'Administrator':
                        $form->display('wid', trans('admin::lang.wid'))->with(function ($wid) {
                            return Warehouse::find($wid)->w_name;
                        });
                        break;
                    default:
                        $form->hidden('wid',trans('admin::lang.wid'))->value(Admin::user()->wid); 
                }
                $form->display('sales_id', '業務'.trans('admin::lang.salesname'))->with(function ($sales_id) {
                    return Sales::find($sales_id)->name;
                });         
            }else{
                $form->date('collect_date',trans('admin::lang.collect_date'))->defaultdate('YYYY-MM-DD');
                $form->html('<font color="#333">系統自動產生</font>',trans('admin::lang.collect_id'));
                //判斷超級使用者
                if(Admin::user()->isAdministrator()){
                    // $form->select('wid',trans('admin::lang.wid'))
                    //     ->options(Warehouse::all()->pluck('w_name','wid'));
                }else{
                    $form->hidden('wid',trans('admin::lang.wid'))->value(Admin::user()->wid);
                    $form->select('sales_id','業務'.trans('admin::lang.salesname'))
                    ->options(Sales::all()->where('wid',Admin::user()->wid)->pluck('name','sales_id'))->rules('required');   
                }   
            }

            $form->textarea('collect_notes', trans('admin::lang.notes'))->rows(2);
            $form->divide();
            $states = [
                'on'  => ['value' => 1, 'text' => '已領貨', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '未領貨', 'color' => 'danger'],
            ];
            $form->switch('collect_check', trans('admin::lang.collect_check'))->states($states);
            // $form->hidden('collect_check_user');

            $states = [
                'on'  => ['value' => 1, 'text' => '已收款', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '未收款', 'color' => 'danger'],
            ];
            $form->switch('receipt_check', trans('admin::lang.receipt_check'))->states($states);
            // $form->hidden('receipt_check_user');
            
            $form->hidden('collect_id',trans('admin::lang.collect_id'));
            $form->hidden('update_user')->value(Admin::user()->id);
            $form->hidden('collect_amount');
            $form->divide();
            $form->button('btn-danger btn-append','+ 領貨商品')->on('click','ShowModal("salescollect_hasstock");');
            
            // $form->display('created_at', 'Created At');
            // $form->display('updated_at', 'Updated At');

             $form->saving(function (Form $form) {

                if(empty(request()->pid)){
                    $error = new MessageBag(['title'=>'提示','message'=>'未填寫配貨商品!']);
                    return back()->withInput()->with(compact('error'));
                }   
                
                // 業務領貨單號編碼:所選擇領貨日期+三位數(自動增加)，共10碼 ex:第一筆 20180202001                
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
                }/*else{
                    $form->ignore(['collect_id']);
                }*/
                
                //領貨確認人

                // if ($form->collect_check_user == true){

                // }
                // if ($form->collect_check_user == true){

                // }
                $scd_amount = request()->amount;
                $scd_salesprice = request()->price;
                $scd_quantity = request()->quantity;
                $scd_notes = request()->notes;
                $stid = request()->stid; 
                $scdid = request()->scdid; //領貨明細id
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
                            'scd_notes'     =>  $scd_notes[$key], //備註
                        ];
                        $stidArray[] = $stid[$key];
                        $total += $scd_amount[$key];
                    }
                    
                    // SalesCollectDetails::where('collect_id',$form->collect_id)->delete();
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
                            'scd_notes'     =>  $scd_notes[$key],
                        ];
                        // SalesAssignDetails::updateorcreate($insertSalesAssignArray);
                        $stidArray[] = $stid[$key];
                        $total += $scd_amount[$key];
                        
                        if (isset($scdid[$key])) { 
                            SalesCollectDetails::updateOrCreate(['scdid' => $deleteScdid[$key]], $insertSalesCollectArray[$key]);
                            $unsetKey = array_search($scdid[$key],$deleteScdid);
                            unset($deleteScdid[$unsetKey]); 
                        }elseif(empty($scdid[$key])){ 
                            SalesCollectDetails::create($insertSalesCollectArray[$key]); 
                        }
                    }
                    SalesCollectDetails::whereIn('scdid',$deleteScdid)->delete();
                }
                $form->collect_amount = $total;
                $form->update_user = Admin::user()->id;
             });
        });
    }
}

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

class SalesAssignController extends Controller
{
    use ModelForm;

    /**
     * 編輯時，回傳 配貨單明細
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
            $stock[$value->stid] = Stock::find($value->stid)->st_type;
        }
        $rowWidth = [33,100,150,60,80,80,80,80,110];
        $rowLeft = [0,33,133,283,343,423,503,583,663];
        $rowTitle = ['','商品編號','商品名','單位','款式','配貨數','單價(業務)','總價','備註'];
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
        Permission::check(['SalesAssign-reader']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.sales_assign'));
            $content->description(trans('admin::lang.list'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_assign')]
            );
            
            $content->row(function (Row $row) {
                $row->column(5, function (Column $column){
                    $count = SalesAssign::where('assign_id', date('Ymd'))->count();
                    if ($count > 0){        
                        $column->append((new Alert('今日已配貨!!'))->style('warning')->icon('cubes'));
                    }else {
                        // $column->append((new Alert('今日未配貨!!'))->style('info')->icon('cubes'));
                    }
                });
            });

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
        Permission::check(['SalesAssign-editor']);
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('admin::lang.sales_assign'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales_assign'), 'url' => '/sales/assign'],
                ['text' => trans('admin::lang.edit')]
            );
            //比對是否為倉庫人員，否則無權訪問編輯及刪除配貨單，超級管理員權限all(暫定)
            // $check_assign_id = SalesAssign::all()->where('said',$id)->pluck('assign_id');
            // $check_wid = str_replace ("0", "",substr($check_assign_id [0],-3));
            // if($check_wid ==  Admin::user()->wid || Admin::user()->isAdministrator()){
        //         $content->body($this->form()->edit($id));
        //     }else{
        //         $error = new MessageBag([
        //             'title'  => trans('admin::lang.deny'),
        //         ]);
        //         return back()->with(compact('error'));
        //     }
            $content->body($this->form()->edit($id));
            $script = <<<SCRIPT
            ShowSalesAssignDetails('$id');
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
        Permission::check(['SalesAssign-creator']);
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
            
            $grid->model()->orderBy('said', 'desc'); // 預設排序
            $grid->filter(function($filter){
                $filter->disableIdFilter();
                $filter->like('assign_id',trans('admin::lang.assign_id'));
                // $filter->between('assign_date', trans('admin::lang.assign_date'))->date();
            });
            //關閉眼睛功能
            $grid->actions(function ($actions) {
                $actions->disableView();
                // 没有`deleter權限角色不顯示刪除按鈕
                if (!Admin::user()->can('SalesAssign-deleter')) {
                    $actions->disableDelete();
                }
                //判斷是否為更新者可編輯及刪除
                $check_wid = str_replace ("0", "",substr($actions->row->assign_id [0],-3));
                if ($check_wid  != Admin::user()->wid) {
                    // $actions->disableDelete();
                    // $actions->disableEdit();
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
                // default:
                //     echo Request::get('assign_date');
            }

            $grid->number('No.')->sortable();
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
                    return Warehouse::find($wid)->w_name;
                })->label('info');
            }

            $grid->assign_amount(trans('admin::lang.assign_total'))->sortable();
            $grid->update_user(trans('admin::lang.update_user'))->display(function($userId) {
                   return Administrator::find($userId)->name;
            });

            //$grid->created_at(trans('admin::lang.created_at'));
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
        return Admin::form(SalesAssign::class, function (Form $form) {

            //判斷是否為編輯狀態     
            if(strpos(url()->current(), '/edit') !== false) {
                $form->date('assign_date',trans('admin::lang.assign_date'))->readOnly();
                $form->text('assign_id',trans('admin::lang.assign_id'))->readOnly();
                
            }else{
                $form->date('assign_date',trans('admin::lang.assign_date'))->defaultdate('YYYY-MM-DD');
                $form->html('<font color="#333">系統自動產生</font>',trans('admin::lang.assign_id'));
                
            }
            //判斷超級使用者
            if(Admin::user()->isAdministrator()){
                // $form->select('wid',trans('admin::lang.wid'))
                //     ->options(Warehouse::all()->pluck('w_name','wid'))->readOnly();
            }else{
                $form->hidden('wid',trans('admin::lang.wid'))->value(Admin::user()->wid);
            }
            
            $form->hidden('assign_id',trans('admin::lang.assign_id'));
            $form->textarea('assign_notes',trans('admin::lang.notes'));
            $form->hidden('update_user')->value(Admin::user()->id);
            $form->hidden('assign_amount');
            $form->divide();
            
            //btn-append有另外寫js的append功能
            //ShowModal("hasstock"):查詢所屬倉庫商品庫存
            $form->button('btn-danger btn-append','+ 配貨商品')->on('click','ShowModal("hasstock2");');

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
                $sadid = request()->sadid;
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
                            'pid'           =>  $pid,
                            'assign_id'     =>  $form->assign_id,
                            'stid'          =>  $stid[$key],
                            'sad_amount'    =>  $sad_amount[$key],
                            'sad_salesprice'=>  $sad_salesprice[$key],
                            'sad_quantity'  =>  $sad_quantity[$key],
                            'sad_notes'     =>  $sad_notes[$key],
                        ];
                        $stidArray[] = $stid[$key];
                        $total += $sad_amount[$key];
                    }
                    SalesAssignDetails::where('assign_id',$form->assign_id)->delete();
                    SalesAssignDetails::insert($dataArray);
                }
                /*****************************
                 * 
                 * 編輯 配貨單明細
                 * 
                 *****************************/
                elseif(request()->action == 'edit'){
                    // $retailRedid = SalesAssignDetails::where('assign_id',$form->assign_id)->pluck('td_quantity','tdid')->toArray();
                    dd(request()->pid);
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
                        
                        // if (isset($sadid)) { 
                        //     SalesAssignDetails::updateOrCreate(['sadid' => $sadid], $insertSalesAssignArray[$key]); 
                        // }else { 
                        //     SalesAssignDetails::create($insertSalesAssignArray[$key]); 
                        // }
                    }

                }
                $form->assign_amount = $total;
                $form->update_user = Admin::user()->id;
            });
        });       
    }
}

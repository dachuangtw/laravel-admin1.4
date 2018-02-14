<?php

namespace App\Admin\Controllers;

use App\InventoryDetails;
use App\Inventory;
use App\ProductIndex;
use Encore\Admin\Auth\Permission;
use Illuminate\Support\MessageBag;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Layout\Column;
use Encore\Admin\Widgets\Box;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\Request;

class InventoryDetailsController extends Controller
{
    use ModelForm;

    public function getdata($indid)
    {
        $inventoryDetails = InventoryDetails::find($indid);
        $productDetails = ProductIndex::find($inventoryDetails->pid);
        
        /* [0]indid|[1]商品名|[2]src|[3]款式|[4]目前庫存|[5]盤點數|[6]備註|[7]已盤點|[8]是盤點人 */
        $returnData = [
            '0' =>  $inventoryDetails->indid,
            '1' =>  $productDetails->p_name,
            '2' =>  $productDetails->p_pic,
            '3' =>  $inventoryDetails->ind_type,
            '4' =>  $inventoryDetails->ind_stock,
            '5' =>  $inventoryDetails->ind_quantity,
            '6' =>  $inventoryDetails->ind_notes,
            '7' =>  $inventoryDetails->ind_at ? '1' : '0',
            '8' =>  ($inventoryDetails->ind_user == Admin::user()->id) ? '1' : '0',
        ];

        return implode("|",$returnData);

    }
    
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index($inid)
    {
        Permission::check(['Inventory-Editor']);

        return Admin::content(function (Content $content) use ($inid) {
            
            $content->header(trans('admin::lang.inventory'));
            $content->description(trans('admin::lang.counting'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.inventory'), 'url' => 'inventory'],
                ['text' => trans('admin::lang.counting')]
            );

            if(Inventory::find($inid)->in_checked){
                $error = new MessageBag(['title'=>'提示','message'=>'已盤點確認，不可操作']);
                return back()->withInput()->with(compact('error'));
            }

            $content->row(function (Row $row) use ($inid) {
                /**
                 * 功能：搜尋商品、已盤點(管理者本人盤點的商品)、未盤點的商品
                 */
                $row->column(6, function (Column $column) use ($inid) {

                    $url = admin_url('inventory/'.$inid.'/details');
                    $csrf_field = csrf_field();
                    $btns = <<<SEARCH
                    <form method="GET" id="searchform">
                    <div class="select2-search">
                    <div class="flex-row">
                        <div class="flex-col-xs no-padding padding-right-10">
                            <div class="input-group">
                                <input type="hidden" name="type" id="hiddentype" value="search">
                                <input class="form-control no-border-right" placeholder="請輸入搜尋的商品名 或 商品編號" type="text" name="search">
                                <span class="input-group-btn">
                                <button class="btn btn-transparent-grey2" onclick="$('#hiddentype').val('search');$('#searchform').submit();"> <i class="fa fa-search"></i> </button>
                                </span>
                            </div>
                        </div>
                        $csrf_field
                        <a href="$url" class="btn btn-sm btn-default"> 返回 </a>
                        <button class="btn btn-sm btn-primary" onclick="$('#hiddentype').val('myinventory');$('#searchform').submit();"> 已盤點 </button>
                        <button class="btn btn-sm btn-success" onclick="$('#hiddentype').val('notyet');$('#searchform').submit();"> 未盤點 </button>
                    </div>
                    </div>
                    </form>
SEARCH;

                    $column->append((new Box(trans('admin::lang.search'), $btns))->collapsable()->style('success'));
                });
            });

            $in_number = Inventory::find($inid)->in_number;
            $search = Request::get('search');
            $type = Request::get('type');
            if($type == 'search'){
                if(empty($search)){
                    $details = InventoryDetails::where('in_number',$in_number)->get();
                }else{
                    $pids = ProductIndex::where('p_name', 'like', '%'.$search.'%')->orWhere('p_number', 'like', '%'.$search)->pluck('pid');
                    $details = InventoryDetails::where('in_number', $in_number)->whereIn('pid',$pids)->get();
                }
            }elseif($type == 'notyet'){
                $details = InventoryDetails::where('in_number',$in_number)->where('ind_at',NULL)->get();
            }elseif($type == 'myinventory'){
                $details = InventoryDetails::where('in_number',$in_number)->where('ind_user',Admin::user()->id)->get();
            }else{
                $details = InventoryDetails::where('in_number',$in_number)->get();
            }
            foreach($details as $key => $detail){
                $details[$key]->p_name = ProductIndex::find($detail->pid)->p_name;
            }
            
            $content->row(function (Row $row) use($details) {
                $row->column(12, function (Column $column) use($details) {
                    $column->append((new Box(trans('admin::lang.list'), view('admin::inventorylist',compact('details'))))->style('info'));
                });
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

            $content->header(trans('admin::lang.inventory'));
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

            $content->header('header');
            $content->description('description');

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
        return Admin::grid(InventoryDetails::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(InventoryDetails::class, function (Form $form) {

            $form->hidden('ind_quantity');
            $form->hidden('ind_notes');

            $form->hidden('ind_user');
            $form->hidden('ind_at');
            $form->hidden('update_user');
            $form->hidden('updated_at');

            $form->saving(function(Form $form) {
                $form->ind_at = $form->updated_at = date('Y-m-d H:i:s');
                $form->ind_user = $form->update_user = Admin::user()->id;
            });
            $form->saved(function(Form $form) {
                return back();
            });
        });
    }
}

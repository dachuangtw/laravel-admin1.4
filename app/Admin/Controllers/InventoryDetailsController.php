<?php

namespace App\Admin\Controllers;

use App\InventoryDetails;
use App\Inventory;
use App\ProductIndex;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Layout\Column;
use Encore\Admin\Widgets\Box;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class InventoryDetailsController extends Controller
{
    use ModelForm;

    public function getdata($indid)
    {
        $inventoryDetails = InventoryDetails::find($indid);
        $productDetails = ProductIndex::find($inventoryDetails->pid);
        
        /* [0]indid|[1]商品名|[2]src|[3]款式|[4]目前庫存|[5]盤點數|[6]已盤點|[7]是盤點人 */
        $returnData = [
            '0' =>  $inventoryDetails->indid,
            '1' =>  $productDetails->p_name,
            '2' =>  $productDetails->p_pic,
            '3' =>  $inventoryDetails->ind_type,
            '4' =>  $inventoryDetails->ind_stock,
            '5' =>  $inventoryDetails->ind_quantity,
            '6' =>  $inventoryDetails->ind_at ? '1' : '0',
            '7' =>  ($inventoryDetails->ind_user == Admin::user()->id) ? '1' : '0',
        ];

        return implode("|",$returnData);

    }
    public function setdata($indid)
    {
        $inventoryDetails = InventoryDetails::find($indid);

    }
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index($inid)
    {
        return Admin::content(function (Content $content) use ($inid) {
            
            $content->header(trans('admin::lang.inventory'));
            $content->description(trans('admin::lang.counting'));
            
            $content->row(function (Row $row) {
                /**
                 * 功能：搜尋商品，
                 * 可見欄位：商品名
                 */
                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('inventory'));
                    $form->method('GET');

                    /**
                     * !important Bug：搜尋功能和filter密不可分...
                     * 這裡要什麼欄位，filter就必須有那個欄位，才能正常搜尋
                     */                    
                    $form->text('search', trans('admin::lang.p_name'));

                    $form->disableSubmit()->disableReset()->enableSearch();

                    $column->append((new Box(trans('admin::lang.search'), $form))->collapsable()->style('success'));
                });

            });
            $in_number = Inventory::find($inid)->in_number;
            $details = InventoryDetails::where('in_number',$in_number)->get();
            foreach($details as $key => $detail){
                $details[$key]->p_name = ProductIndex::find($detail->pid)->p_name;
            }
            $content->row(function (Row $row) use($details) {
                /**
                 * 功能：搜尋商品，
                 * 可見欄位：商品名
                 */
                $row->column(12, function (Column $column) use($details) {

                    $column->append((new Box(trans('admin::lang.list'), view('admin::inventorylist',compact('details'))))->style('info'));
                });

            });
            // $content->body(view('admin::inventorylist',compact('details')));
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

            $content->header('header');
            $content->description('description');

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

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}

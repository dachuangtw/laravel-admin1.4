<?php

namespace App\Admin\Controllers;
use App\Shipping;
use App\Records;
use App\User;
use App\Stock;
use App\Purchase_list;
use App\Message;
use App\Product;
use App\ProductIndex;
use App\Payment;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;

class RecordsController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('admin::lang.record'));
            $content->description(trans('admin::lang.list'));
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
            $content->header(trans('admin::lang.record'));
            $content->description(trans('admin::lang.list'));
            $content->body($this->editform()->edit($id));
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
            $content->header(trans('admin::lang.record'));
            $content->description(trans('admin::lang.list'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.record'), 'url' => '/records'],
                ['text' => trans('admin::lang.create')]
            );      
            $content->body($this->form());
        });
    }


    public function view($id)
    {
        $result = DB::table('product_index')
        ->join('record', 'product_index.pid','=','record.pid' )
        ->where('record.rid','=',$id)
        ->select('product_index.p_name', 'product_index.p_price',"record.amout")
        ->get()->toArray();        
             
        $headers = ['商品名稱', '價格', '數量'];
        $table = new Table($headers, $result);
        $table->class('table table-hover');
        return $table->render();
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Records::class, function (Grid $grid) {
            $grid->rid(trans('admin::lang.rid'));
            $grid->column('user_id',trans('admin::lang.buyer'))->value(function ($id) {
                $BuyerName = User::where('id', $id)->pluck('name')->toArray();
                if(!empty($BuyerName))
                    return $BuyerName['0'];
                else
                    return '';
            });
            $grid->column('payment',trans('admin::lang.payment'))->value(function ($id) {
                $payment = Payment::where('pay_id', $id)->pluck('payment')->toArray();
                if(!empty($payment))
                    return $payment['0'];
                else
                    return '';
            });

            $grid->column('shipping',trans('admin::lang.shipping'))->value(function ($id) {
                $shipping = Shipping::where('ship_id', $id)->pluck('ship_name')->toArray();
                if(!empty($shipping))
                    return $shipping['0'];
                else
                    return '';
            });
        
            $grid->total(trans('admin::lang.buy_total'));
            $grid->created_at(trans('admin::lang.buy_at'));
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Records::class, function (Form $form) {
            $form->tab('購買資料', function (Form $form) {
                $form->text('rid', trans('admin::lang.rid'));
                
                $form->select('user_id', trans('admin::lang.buyer'))->options(
                    User::all()->pluck('name', 'id')
                    ->transform(function ($item, $key) {
                        return $key.' - '.$item;
                    })->toArray());

                $form->select('payment', trans('admin::lang.payment'))->options(
                    Payment::all()->pluck('payment'));

                $form->select('shipping', trans('admin::lang.shipping'))->options(
                    Shipping::all()->pluck('ship_name'));

                $form->butdate('created_at', trans('admin::lang.buy_at'));

                $form->radio('status', trans('admin::lang.status'))  
                ->options(['1' =>'出貨','0' =>'未出貨'])->default('0');

            })->tab('詳細資料', function (Form $form) {
                //方法一 新增就submit 計算總價
                $form->hasMany('record','商品清單', function (Form\NestedForm $form) {          
                    $form->select('product_id', trans('admin::lang.p_name'))->options(
                        ProductIndex::all()->pluck('p_price', 'p_name')->transform(function ($price, $name) {
                            return $name.' - $NTD '.$price;
                        })->toArray())->rules('required');
                    $form->number('amout', trans('admin::lang.buy_amout'));
                });

               //方法二 可以顯示但寫入有問題
               $form->button('btn-danger btn-append','+ 商品清單')->on('click','ShowModal("product");'); 

            })->tab('意見回饋', function (Form $form) {
                $form->textarea('admin_msg', trans('admin::lang.notes'));
            });
        });
    }

    protected function editform()
    {
        return Admin::form(Records::class, function (Form $form) {
            $form->tab('購買資料', function (Form $form) {
                $form->display('rid', trans('admin::lang.rid'));
                
                $form->select('user_id', trans('admin::lang.buyer'))->options(
                    User::all()->pluck('name', 'id')->transform(function ($item, $key) {
                        return $key.' - '.$item;
                    })->toArray());

                $form->select('payment', trans('admin::lang.payment'))->options(
                    Payment::all()->pluck('payment')->transform(function ($name) {
                        return $name;
                    })->toArray());

                $form->select('shipping', trans('admin::lang.shipping'))->options(
                    Shipping::all()->pluck('ship_cost', 'ship_name')->transform(function ($price, $name) {
                        return $name.' - $NTD '.$price;
                    })->toArray());

                $form->butdate('created_at', trans('admin::lang.buy_at'));

                $form->radio('status', trans('admin::lang.status'))  
                ->options(['1' =>'出貨','0' =>'未出貨'])->default('0');

            })->tab('詳細資料', function (Form $form) {
                //瀏覽 編輯 新增
                //問題:上面是Records但這裡要用PurchaseList
 
                $form->hasMany('record','商品清單', function (Form\NestedForm $form) {          
 
                    $form->select('product_id', trans('admin::lang.p_name'))->options(
                        ProductIndex::all()->pluck('p_price', 'p_name')->transform(function ($price, $name) {
                            return $name.' - $NTD '.$price;
                        })->toArray())->rules('required');

                    $form->number('amout', trans('admin::lang.buy_amout'));
                });

                //計算總價
                $form->display('total', trans('admin::lang.buy_total'));
            })->tab('意見回饋', function (Form $form) {
                $form->select('rid', trans('admin::lang.cus_msg'))->options(
                    Message::all()->pluck('cus_msg', 'record_id')->transform(function ($item, $key) {
                        return $item;
                    })->toArray());
                $form->textarea('admin_msg', trans('admin::lang.admin_msg'));
            });
        });
    }


   
}

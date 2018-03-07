<?php

namespace App\Admin\Controllers;

use App\Sales;
use App\Warehouse;
use App\WebLocation;
use Encore\Admin\Auth\Permission;
use App\Admin\Extensions\Tools\SalesResign;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use App\Admin\Extensions\ExcelExpoter;

class SalesController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        Permission::check(['Sales-Reader']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.sales'));
            $content->description(trans('admin::lang.list'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales')]
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
        Permission::check(['Sales-Editor']);
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('admin::lang.sales'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales'), 'url' => '/sales'],
                ['text' => trans('admin::lang.edit')]
            );

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
        Permission::check(['Sales-Creator']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.sales'));
            $content->description(trans('admin::lang.new'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.sales'), 'url' => '/sales'],
                ['text' => trans('admin::lang.new')]
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
        return Admin::grid(Sales::class, function (Grid $grid) {
            $grid->actions(function ($actions) {
                $actions->disableView();
                // 没有`deleter權限角色不顯示刪除按鈕
                if (!Admin::user()->can('Sales-Deleter')) {
                    $actions->disableDelete();
                }
            });

            //接收查詢在職or離職
            if (in_array(Request::get('resign'), ['f', 't'])) {
                $grid->model()->where('resign', Request::get('resign'));
            };
            $grid->tools(function ($tools) {
                $tools->append(new SalesResign());
            });
            $grid->model()->orderBy('id', 'desc');
            //查詢過濾器
            $grid->filter(function($filter){
                // 如果过滤器太多，可以使用弹出模态框来显示过滤器.
                // $filter->useModal();
                // 禁用id查询框
                $filter->disableIdFilter();
                if(Admin::user()->isAdministrator()){
                    $filter->where(function ($query) {
                        $query->where('warehouse_id',  "{$this->input}");
                    }, trans('admin::lang.warehouse'))->select(
                        Warehouse::all()->pluck('name', 'id')->toArray()
                    );
                }
                // sql: ... WHERE `user.name` LIKE "%$name%";
                $filter->like('name', trans('admin::lang.salesname'));
                // $filter->like('sales_id', trans('admin::lang.sales_id'));
            });

            // $grid->sales_id(trans('admin::lang.sales_id'))->sortable();
            $grid->number('No.');
            $grid->rows(function ($row, $number) {
                $row->column('number', $number+1);
            });

            //超級管理員可看到所有業務資料，其他為各倉庫
            if(Admin::user()->isAdministrator()){
                $grid->warehouse_id(trans('admin::lang.location_area'))->sortable()->display(function($wid) {
                    return Warehouse::find($wid)->name;
                })->label('info');
            }else{
                $grid->model()->where('warehouse_id', '=', Admin::user()->wid);
            }
            $grid->column('name',trans('admin::lang.salesname').'/'.trans('admin::lang.nickname'))->display(function ($name) {
                return "$name".' <font size="2"  color="blue">('.$this->nickname.')';
            });
            $grid->resign(trans('admin::lang.resign'))->value(function ($resign) {
                return $resign == 'f' ? "<span class='label label-success'>在職</span>" : "<span class='label label-danger'>離職</span>";
            })->sortable();
            $grid->collect_at(trans('admin::lang.collect_at'));
            $grid->created_at( trans('admin::lang.created_at'));
            $grid->updated_at( trans('admin::lang.updated_at'));

            //excel 匯出設定
            //指定匯出Excel的資料庫欄位(不可使用關聯之資料庫欄位)
            $titles = ['name','warehouse_id','nickname','cellphone', 'start_work_date', 'end_work_date', 'resign'];

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
                'warehouse_id'  =>  [
                    'dbname' =>  'warehouse',
                    'id' =>  'id',
                    'target' =>  'name',
                ],
            ];
            $exporter->setForeignKeys($foreignKeys);
            $exporter->setDetails($titles,'業務資料表'.date('Ymd'),Admin::user()->name);
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
        return Admin::form(Sales::class, function (Form $form) {
            $form->model()->makeVisible('password');

            $form->tab('基本資料', function (Form $form) {

                $form->display('id', trans('admin::lang.sales_id'));
                $method = Request::method();
                $rules = ($method === 'PUT') ? 'required' : 'required|unique:mysql2.sales,account';
                $form->text('account','帳號')->rules($rules);
                $form->password('password', trans('admin::lang.password'))->rules('required|confirmed')->default(function ($form) {
                    return $form->model()->password;
                });

                $form->password('password_confirmation', trans('admin::lang.password_confirmation'))->rules('required')
                ->default(function ($form) {
                    return $form->model()->password;
                });
                $form->ignore(['password_confirmation']);

                $form->divide();
                $form->text('name', trans('admin::lang.salesname'))->rules('required');
                $form->text('nickname', trans('admin::lang.nickname'));

                if(Admin::user()->isAdministrator()){
                    //超級管理員可以自行選擇倉庫
                    $form->select('warehouse_id', trans('admin::lang.location_area'))
                    ->options(Warehouse::all()->pluck('name','id'))->rules('required');
                }else{
                    //非超級管理員使用本身綁定的倉庫id
                    $form->hidden('warehouse_id')->default(Admin::user()->wid);
                }
                // $form->multipleSelect('store_location',trans('admin::lang.web_location'))
                // ->options(WebLocation::all()->pluck('name', 'id'));

                $form->divide();
                $form->radio('resign', trans('admin::lang.resign'))->options(['t' => '是','f' => '否'])->default('f');
                $form->dateRange('start_work_date', 'end_work_date',  trans('admin::lang.start_end_work_date'));

            })->tab('聯絡資訊', function (Form $form) {

                $form->mobile('cellphone',trans('admin::lang.cellphone'))->options(['mask' => '9999 999 999']);;
                $form->textarea('remarks',trans('admin::lang.notes'));

            })->tab('帳號資訊', function (Form $form) {

                $form->display('client_ip',trans('admin::lang.client_ip'));
                $form->display('client_agent',trans('admin::lang.client_agent'));
                $form->display('logged_in_at',trans('admin::lang.logged_in_at'));
                $form->display('collect_at',trans('admin::lang.collect_at'));

                $form->divide();
                $form->display('created_at', trans('admin::lang.created_at'));
                $form->display('updated_at', trans('admin::lang.updated_at'));
            });

            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
            });


        });
    }
}

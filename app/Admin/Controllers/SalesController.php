<?php

namespace App\Admin\Controllers;

use App\Sales;
use App\Warehouse;
use App\WebLocation;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

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
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.sales'));
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

            $content->header(trans('admin::lang.list'));
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

            $content->header(trans('admin::lang.list'));
            $content->description(trans('admin::lang.new'));

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

            $grid->sid(trans('admin::lang.sid'))->sortable();
            $grid->wid(trans('admin::lang.wid'))->sortable();
            $grid->name(trans('admin::lang.salesname'));
            $grid->collect_at(trans('admin::lang.collect_at'));
            $grid->created_at( trans('admin::lang.created_at'));
            $grid->updated_at( trans('admin::lang.updated_at'));
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
                $form->display('sid', '業務編號');

                $warehouse = Warehouse::all('wid','w_name');
                $warehouse = $warehouse->toArray();
                foreach($warehouse as $option){
                    $optionArray[$option['wid']] = $option['w_name'];
                }

                $form->select('wid', trans('admin::lang.warehouse'))->options(
                $optionArray
                );
                $form->email('email', 'Email')->rules('required');
                $form->password('password', trans('admin::lang.password'))->rules('required|confirmed')->default(function ($form) {
                    return $form->model()->password;
                });
                $form->password('password_confirmation', trans('admin::lang.password_confirmation'))->rules('required')
                ->default(function ($form) {
                    return $form->model()->password;});
                $form->ignore(['password_confirmation']
                );
                $form->text('name', trans('admin::lang.salesname'))->rules('required');
                $form->text('nickname', trans('admin::lang.nickname'))->rules('required');
            
                $form->display('created_at', trans('admin::lang.created_at'));
                $form->display('updated_at', trans('admin::lang.updated_at'));

                $form->display('client_ip',trans('admin::lang.client_ip'));
                $form->display('client_agent',trans('admin::lang.client_agent'));
                $form->display('logged_in_at',trans('admin::lang.logged_in_at'));        
                $form->display('collect_at',trans('admin::lang.collect_at'));    
            })->tab('聯絡資訊', function (Form $form) {

                $form->mobile('cellphone',trans('admin::lang.cellphone'));

                $form->multipleSelect('store_location',trans('admin::lang.web_location'))->options(WebLocation::all()->pluck('store_name', 'id'));

                $form->textarea('remarks',trans('admin::lang.notes'));
            });
            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
            });

        });
    }
}

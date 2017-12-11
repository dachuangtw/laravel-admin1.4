<?php

namespace App\Admin\Controllers;

use App\ProductIndex;
use App\Warehouse;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ProductIndexController extends Controller
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

            $content->header(trans('admin::lang.product_index'));
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

            $content->header(trans('admin::lang.product_index'));
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

            $content->header(trans('admin::lang.product_index'));
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
        return Admin::grid(ProductIndex::class, function (Grid $grid) {

            $grid->pid('ID')->sortable();
            $grid->p_number(trans('admin::lang.product_number'))->sortable();
            $grid->p_name(trans('admin::lang.name'));
            $grid->p_salesprice(trans('admin::lang.product_salesprice'));
            $grid->p_costprice(trans('admin::lang.product_costprice'));

            $grid->updated_at(trans('admin::lang.updated_at'));
            $grid->created_at(trans('admin::lang.created_at'));
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(ProductIndex::class, function (Form $form) {

            $form->text('p_name', trans('admin::lang.product_name'))->rules('required');

            $warehouse = Warehouse::all('wid','w_name');
            $warehouse = $warehouse->toArray();
            foreach($warehouse as $option){
                $optionArray[$option['wid']] = $option['w_name'];
            }

            $form->select('wid', trans('admin::lang.warehouse'))->options(
                $optionArray
            );
            $form->image('p_pic', trans('admin::lang.product_pic'))->move('product/', time());
            $form->textarea('p_description', trans('admin::lang.description'))->rows(5);
            $form->text('p_number', trans('admin::lang.product_number'));
            $form->currency('p_price', trans('admin::lang.product_price'))->options(['digits' => 0]);
            $form->currency('p_retailprice', trans('admin::lang.product_retailprice'))->options(['digits' => 0]);
            $form->currency('p_specialprice', trans('admin::lang.product_specialprice'))->options(['digits' => 0]);
            $form->currency('p_salesprice', trans('admin::lang.product_salesprice'))->options(['digits' => 0]);
            $form->currency('p_costprice', trans('admin::lang.product_costprice'))->options(['digits' => 0]);



            //$form->text('p_pic', trans('admin::lang.description'))->help('help...');


            // $form->text('p_pic', trans('admin::lang.description'))->rules('required');
            // $form->text('p_name', trans('admin::lang.name'))->rules('required');
            // $form->text('p_pic', trans('admin::lang.slug'))->rules('required');
            // $form->text('p_name', trans('admin::lang.name'))->rules('required');
            
            // $form->multipleSelect('permissions', trans('admin::lang.permissions'))->options(Permission::all()->pluck('name', 'id'));

            
            // $table->integer('wid')->unsigned()->index()->comment('倉庫id');
            // $table->string('p_name',50)->comment('商品名稱');
            // $table->string('p_pic',100)->comment('商品主圖');
            // $table->text('p_images')->comment('商品副圖(用|分隔)');
            // $table->text('p_description')->comment('商品說明');

            // $table->string('p_number',25)->comment('商品編號');
            // $table->integer('p_price')->default(0)->comment('定價'); 
            // $table->integer('p_retailprice')->default(0)->comment('售價'); 
            // $table->integer('p_specialprice')->default(0)->comment('優惠價');
            // $table->integer('p_salesprice')->default(0)->comment('業務價');
            // $table->integer('p_costprice')->default(0)->comment('進價');

            // $table->text('p_category')->comment('商品分類勾選(用|分隔)'); 
            // $table->text('p_series')->comment('主題系列勾選(用|分隔)');
            // $table->text('p_notes')->comment('備註');
            // $table->boolean('showfront')->default(false)->comment('前台顯示');
            // $table->string('update_user',25)->comment('最後更新者');

            $form->display('created_at', trans('admin::lang.created_at'));
            $form->display('updated_at', trans('admin::lang.updated_at'));
        });
    }
}

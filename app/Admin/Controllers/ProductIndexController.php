<?php

namespace App\Admin\Controllers;

use App\ProductIndex;
use App\Warehouse;
use App\ProductSeries;
use App\ProductCategory;

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
            $grid->p_pic(trans('admin::lang.product_pic'))->display(function ($p_pic) {                
                return "<img src='".rtrim(config('admin.upload.host'), '/').'/'.$p_pic."' style='max-width: 200px;max-height: 200px;'/>";            
            });
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

            
            $form->text('p_number', trans('admin::lang.product_number'));
            $form->text('p_name', trans('admin::lang.product_name'))->rules('required');
            $form->select('wid', trans('admin::lang.warehouse'))->options(
                Warehouse::all()->pluck('w_name', 'wid')
            );
            $form->textarea('p_description', trans('admin::lang.description'))->rows(5);
           
            $form->image('p_pic', trans('admin::lang.product_pic'))->uniqueName()->move('product');
            $form->multipleImage('p_images', trans('admin::lang.product_images'));

            $form->currency('p_price', trans('admin::lang.product_price'))->options(['digits' => 0]);
            $form->currency('p_retailprice', trans('admin::lang.product_retailprice'))->options(['digits' => 0]);
            $form->currency('p_specialprice', trans('admin::lang.product_specialprice'))->options(['digits' => 0]);
            $form->currency('p_salesprice', trans('admin::lang.product_salesprice'))->options(['digits' => 0]);
            $form->currency('p_staffprice', trans('admin::lang.product_staffprice'))->options(['digits' => 0]);
            $form->currency('p_costprice', trans('admin::lang.product_costprice'))->options(['digits' => 0]);

            $form->multipleSelect('p_category', trans('admin::lang.product_category'))->options(
                ProductCategory::all()->pluck('pc_name', 'pcid')
            );
            $form->checkbox('p_series', trans('admin::lang.product_series'))->options(
                ProductSeries::all()->pluck('ps_name', 'psid')
            );
            $states = [
                'on'  => ['value' => 1, 'text' => '顯示', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '隱藏', 'color' => 'danger'],
            ];
            
            $form->switch('showfront', trans('admin::lang.showfront'))->states($states)->default(1);

            //$form->text('p_pic', trans('admin::lang.description'))->help('help...');


            // $form->text('p_pic', trans('admin::lang.description'))->rules('required');
            // $form->text('p_name', trans('admin::lang.name'))->rules('required');
            
            // $form->multipleSelect('permissions', trans('admin::lang.permissions'))->options(Permission::all()->pluck('name', 'id'));


            // $table->text('p_category')->comment('商品分類勾選(用|分隔)'); 
            // $table->text('p_notes')->comment('備註');
            // $table->string('update_user',25)->comment('最後更新者');

            $form->display('updated_at', trans('admin::lang.updated_at'));
            $form->display('created_at', trans('admin::lang.created_at'));
        });
    }
}

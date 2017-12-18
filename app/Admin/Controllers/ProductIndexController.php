<?php

namespace App\Admin\Controllers;

use App\ProductIndex;
use App\Warehouse;
use App\ProductSeries;
use App\ProductCategory;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Box;
use Illuminate\Support\MessageBag;

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

            $content->row(function (Row $row) {
                $row->column(6, function (Column $column) {

                    /**
                     * 快速新增，
                     * 可見欄位：商品名、業務價、成本價
                     * 隱藏欄位：最後更新者
                     */
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('product'));

                    $form->text('p_name', trans('admin::lang.product_name'))->rules('required');

                    $form->currency('p_salesprice', trans('admin::lang.product_salesprice'))->options(['digits' => 0]);
                    $form->currency('p_costprice', trans('admin::lang.product_costprice'))->options(['digits' => 0]);
                    
                    $form->hidden('update_user')->default(Admin::user()->id);

                    $column->append((new Box(trans('admin::lang.quicknew'), $form))->style('info'));
                });

            });
            $content->row(function (Row $row) {
                $row->column(12, $this->grid());

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

            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->like('p_name','名稱查詢');
            });
            $grid->pid('ID')->sortable();
            $grid->p_number(trans('admin::lang.product_number'))->sortable();
            $grid->p_name(trans('admin::lang.name'));
            $grid->p_pic(trans('admin::lang.product_pic'))->display(function ($p_pic) {                
                return "<img src='".rtrim(config('admin.upload.host'), '/').'/'.$p_pic."' style='max-width:50px;max-height:50px;' onerror='this.src=\"".config('app.url')."/images/404.jpg\"'/>";            
            });
            $grid->p_salesprice(trans('admin::lang.product_salesprice'));
            $grid->p_costprice(trans('admin::lang.product_costprice'));
            $grid->stock(trans('admin::lang.product_stock'))->sum('s_stock')->value(function ($stock) {
                if(!empty($stock)){
                    return $stock;
                }
                return "<span class='label label-warning'>Error</span>";
            });
            // $grid->stock(trans('admin::lang.product_stock'))->sum('s_stock');
            $grid->showfront('前台顯示')->value(function ($showfront) {
                return $showfront ? "<span class='label label-success'>Yes</span>" : "<span class='label label-danger'>No</span>";
            });
            $grid->showsales('業務顯示')->value(function ($showsales) {
                return $showsales ? "<span class='label label-success'>Yes</span>" : "<span class='label label-danger'>No</span>";
            });
            $grid->updated_at(trans('admin::lang.updated_at'));
            $grid->model()->orderBy('pid', 'desc');
            $grid->define_preg(url('/admin/product'),'返回');
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

            $form->tab('商品資訊', function ($form) {
                
                $form->text('p_number', trans('admin::lang.product_number'));
                $form->text('p_name', trans('admin::lang.product_name'))->rules('required');

                $form->multipleSelect('p_category', trans('admin::lang.product_category'))->options(
                    ProductCategory::all()->pluck('pc_name', 'pcid')
                );
                $form->checkbox('p_series', trans('admin::lang.product_series'))->options(
                    ProductSeries::all()->pluck('ps_name', 'psid')
                );           
                $form->image('p_pic', trans('admin::lang.product_pic'))->uniqueName()->move('product');
                $form->multipleImage('p_images', trans('admin::lang.product_images'));
                $form->textarea('p_description', trans('admin::lang.description'))->rows(5);
                $states = [
                    'on'  => ['value' => 1, 'text' => '顯示', 'color' => 'success'],
                    'off' => ['value' => 0, 'text' => '隱藏', 'color' => 'danger'],
                ];            
                $form->switch('showfront', trans('admin::lang.showfront'))->states($states)->default(1);
                $form->switch('shownew', trans('admin::lang.shownew'))->states($states)->default(1);
                
            })->tab('價格/業務', function ($form) {
                                   
                $form->currency('p_price', trans('admin::lang.product_price'))->options(['digits' => 0]);
                $form->currency('p_retailprice', trans('admin::lang.product_retailprice'))->options(['digits' => 0]);
                $form->currency('p_specialprice', trans('admin::lang.product_specialprice'))->options(['digits' => 0]);
                $form->currency('p_salesprice', trans('admin::lang.product_salesprice'))->options(['digits' => 0]);
                // $form->currency('p_staffprice', trans('admin::lang.product_staffprice'))->options(['digits' => 0]);
                $form->currency('p_costprice', trans('admin::lang.product_costprice'))->options(['digits' => 0]);

                $states = [
                    'on'  => ['value' => 1, 'text' => '顯示', 'color' => 'success'],
                    'off' => ['value' => 0, 'text' => '隱藏', 'color' => 'danger'],
                ]; 

                $form->switch('showsales', trans('admin::lang.showsales'))->states($states)->default(1);
                $form->textarea('p_notes', trans('admin::lang.salesman').trans('admin::lang.notes'))->rows(5);
               
            })->tab('款式/庫存', function ($form) {
                $form->hasMany('stock','款式庫存', function (Form\NestedForm $form) {
                    $form->text('s_type',trans('admin::lang.product_type'));
                    $form->text('s_barcode',trans('admin::lang.product_barcode'));
                    $form->text('s_notes',trans('admin::lang.notes'));
                    $form->number('s_stock',trans('admin::lang.product_stock'))->default(1);
                    $form->number('s_collect',trans('admin::lang.product_sales'))->default(1);
                });
            
              });
            
            

            

            

            // $form->divide();
            
            
            $form->hidden('update_user')->default(Admin::user()->id);
            $form->display('updated_at', trans('admin::lang.updated_at'));

        });
    }
}

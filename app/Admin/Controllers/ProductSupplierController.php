<?php

namespace App\Admin\Controllers;

use App\ProductSupplier;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Auth\Permission;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Widgets\Table;

class ProductSupplierController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        Permission::check(['ProductSupplier-Reader']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.product_supplier'));
            $content->description(trans('admin::lang.list'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.product_supplier')]
            );                  

            $content->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('supplier'));

                    $form->text('sup_number', trans('admin::lang.sup_number'))->rules('required');
                    $form->text('sup_name', trans('admin::lang.name'))->rules('required');
                    $form->text('sup_alias', trans('admin::lang.alias'));
                    $form->textarea('sup_notes', trans('admin::lang.notes'))->rows(3);
                    $form->hidden('update_user')->default(Admin::user()->id);

                    $column->append((new Box(trans('admin::lang.new'), $form))->style('danger'));
                });
            });
        });
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        return ProductSupplier::tree(function (Tree $tree) {
            $tree->disableCreate();

            $tree->branch(function ($branch) {
                $payload = "&nbsp;<strong>{$branch['sup_number']} - {$branch['sup_name']}</strong>";
                return $payload;
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
        Permission::check(['ProductSupplier-Editor']);
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('admin::lang.product_supplier'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.product_supplier'), 'url' => '/supplier'],
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
        Permission::check(['ProductSupplier-Creator']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.product_supplier'));
            $content->description(trans('admin::lang.create'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.product_supplier'), 'url' => '/supplier'],
                ['text' => trans('admin::lang.create')]
            );            

            $content->body($this->form());
        });
    }
    /**
     * View interface.
     *
     * @param $id
     * @return Content
     */
    public function view($id)
    {
        
        Permission::check(['ProductSupplier-Reader']);

        $supplier = ProductSupplier::find($id)->toArray();

        //忽略不顯示的欄位
        $skipArray = ['supid','sup_sort'];
        //某些角色顯示欄位
        $showArray = [];
        //顯示圖片欄位
        $imgArray = [];

        //置換最近更新者的內容
        $supplier['update_user'] = Administrator::find($supplier['update_user'])->name;

        $header[] = '廠商資訊';
        foreach($supplier as $key => $value){            

            if(in_array($key,$skipArray) || empty($value)){
                if(!(isset($showArray[$key]) && Admin::user()->inRoles($showArray[$key]))){
                    continue;
                }
            }
            
            //欄位中文化
            $newkey = trans('admin::lang.'.$key);

            /**
             * 內容排版
             *    ┬ 圖片 ┬ 主圖(string)
             *    │      └ 副圖(array)---連續印出
             *    │
             *    └ 文字 ┬ 分類、系列(array)---用/分隔
             *           └ 其他(string)
             */


            if(in_array($key,$imgArray)){
                if(is_array($value)){
                    $content = '';
                    foreach($value as $temp){
                        $content .= '<img src="' .rtrim(config('admin.upload.host'), '/').'/'. $temp . '" width="50px" />';
                    }
                    $rows[$newkey] = $content;
                }else{
                    $rows[$newkey] = '<img src="' .rtrim(config('admin.upload.host'), '/').'/'. $value . '" width="100px" />';
                }

            }else{
                if(is_array($value)){
                    $content = '';
                    foreach($value as $temp){
                        if(empty($content))
                            $content = $temp;
                        else
                            $content .= ' / ' . $temp;
                    }
                    $rows[$newkey] = $content;
                }else{
                    $rows[$newkey] = nl2br($value);
                }
            }

            
        }

        $table = new Table($header, $rows);
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
        return Admin::grid(ProductSupplier::class, function (Grid $grid) {

            $grid->sup_number(trans('admin::lang.sup_number'))->sortable();
            $grid->sup_name(trans('admin::lang.name'));

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
        return Admin::form(ProductSupplier::class, function (Form $form) {

            $form->text('sup_number', trans('admin::lang.sup_number'))->rules('required');
            $form->text('sup_name', trans('admin::lang.name'))->rules('required');
            $form->text('sup_alias', trans('admin::lang.alias'));
            $form->textarea('sup_notes', trans('admin::lang.notes'))->rows(3);
            $form->hidden('update_user')->default(Admin::user()->id);
                        
            $form->display('updated_at', trans('admin::lang.updated_at'));
        });
    }
}

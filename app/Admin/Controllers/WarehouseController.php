<?php

namespace App\Admin\Controllers;

use App\Warehouse;

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

class WarehouseController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        Permission::check(['Warehouse-Reader']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.warehouse'));
            $content->description(trans('admin::lang.list'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.warehouse')]
            );            

            // $content->body($this->grid());

            $content->row(function (Row $row) {
                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('warehouse'));

                    $form->text('w_name', trans('admin::lang.name'))->rules('required');
                    $form->option('enableReset',false);

                    $column->append((new Box(trans('admin::lang.new'), $form))->style('danger'));
                });

            });
            $content->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

            });
        });
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        return Warehouse::tree(function (Tree $tree) {
            $tree->disableCreate();

            $tree->branch(function ($branch) {
                $payload = "&nbsp;<strong>{$branch['w_name']}</strong>";
                return $payload;
            });
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
        
        Permission::check(['Warehouse-Reader']);

        $warehouse = Warehouse::find($id)->toArray();

        //忽略不顯示的欄位
        $skipArray = ['wid','parent_id','w_sort'];
        //某些角色顯示欄位
        $showArray = [];
        //顯示圖片欄位
        $imgArray = [];

        $header[] = '倉庫資訊';
        foreach($warehouse as $key => $value){            

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
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        Permission::check(['Warehouse-Editor']);
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('admin::lang.warehouse'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.warehouse'), 'url' => '/warehouse'],
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
        Permission::check(['Warehouse-Creator']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.warehouse'));
            $content->description(trans('admin::lang.create'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.warehouse'), 'url' => '/warehouse'],
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
        return Admin::grid(Warehouse::class, function (Grid $grid) {

            $grid->wid('ID')->sortable();
            $grid->w_name(trans('admin::lang.name'));
            $grid->w_phone(trans('admin::lang.phone'));
            $grid->w_address(trans('admin::lang.address'));

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
        return Admin::form(Warehouse::class, function (Form $form) {

            $form->display('wid', 'ID');
            $form->text('w_name', trans('admin::lang.name'))->rules('required');
            $form->text('w_phone', trans('admin::lang.phone'));
            $form->text('w_address', trans('admin::lang.address'));
            $form->textarea('w_notes', trans('admin::lang.notes'))->rows(5);

            $form->display('created_at', trans('admin::lang.created_at'));
            $form->display('updated_at', trans('admin::lang.updated_at'));
        });
    }
}

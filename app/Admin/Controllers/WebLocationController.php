<?php

namespace App\Admin\Controllers;

use App\WebLocation;
use App\WebArea;
use App\Sales;
use App\Warehouse;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Auth\Permission;

class WebLocationController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        Permission::check(['WebLocation-Reader']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.web_location'));
            $content->description(trans('admin::lang.list'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.web_location')]
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
        Permission::check(['WebLocation-Editor']);
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('admin::lang.web_location'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.web_location'), 'url' => '/web/location'],
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
        Permission::check(['WebLocation-Creator']);
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.web_location'));
            $content->description(trans('admin::lang.new'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.web_location'), 'url' => '/web/location'],
                ['text' => trans('admin::lang.new')]
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
        Permission::check(['WebLocation-Reader']);

        $weblocation = WebLocation::find($id)->toArray();

        //忽略不顯示的欄位
        $skipArray = ['location_id','show','deleted_at'];
        //某些角色顯示欄位
        $showArray = [];
        //顯示圖片欄位
        $imgArray = ['store_picture'];

        //置換內容
        $weblocation['district_id'] = WebArea::find($weblocation['district_id'])->area_name;
        $weblocation['city_id'] = WebArea::find($weblocation['city_id'])->area_name;
        $weblocation['wid'] = Warehouse::find($weblocation['wid'])->w_name;

        $header[] = '店鋪據點資訊';
        foreach($weblocation as $key => $value){            

            if(in_array($key,$skipArray) || empty($value)){
                if(!(isset($showArray[$key]) && Admin::user()->inRoles($showArray[$key]))){
                    continue;
                }
            }
            
            //欄位中文化
            $newkey = trans('admin::lang.'.$key);

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
        return Admin::grid(WebLocation::class, function (Grid $grid) {
            $grid->filter(function($filter){
                // 禁用id查询框
                $filter->disableIdFilter();
                if(Admin::user()->isAdministrator()){
                    $filter->where(function ($query) {
                        $query->where('wid',  "{$this->input}");
                    }, trans('admin::lang.warehouse'))->select(
                        Warehouse::all()->pluck('w_name', 'wid')->toArray()
                    );
                } 
                // sql: ... WHERE `user.name` LIKE "%$name%";
                $filter->like('store_name', trans('admin::lang.store_name'));
            });
            $grid->model()->orderBy('location_id', 'DESC');
            $grid->number('No.')->sortable();
            $grid->rows(function ($row, $number) {
                $row->column('number', $number+1);
            });
            //判斷是否為超級管理員，則只可看所屬倉庫內容
            if(!Admin::user()->isAdministrator()){
                $grid->model()->where('wid',Admin::user()->wid);    
            }else{
                $grid->wid(trans('admin::lang.warehouse'))->sortable()->display(function($wid) {
                    return Warehouse::find($wid)->w_name;
                })->label('info');
            }
            $grid->store_name(trans('admin::lang.store_name'));
            //眼睛彈出視窗的Title，請設定資料庫欄位名稱
            $grid->actions(function ($actions) {
                $actions->setTitleField(['store_name']);
            });

            $states = [
                'on'  => ['value' => 1, 'text' => 'ON', 'color' => 'success'],
                'off' => ['value' => 2, 'text' => 'OFF', 'color' => 'danger'],
            ];
            $grid->column('show',trans('admin::lang.showfront'))->status()->switch($states);

            $states = [
                'on'  => ['value' => 1, 'text' => '開店', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => '閉店', 'color' => 'danger'],
            ];
            $grid->column('store_status',trans('admin::lang.status'))->status()->switch($states);
        });

    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(WebLocation::class, function (Form $form) {
            
            $form->tab('店鋪基本資料', function ($form) {

                $form->text('store_name', trans('admin::lang.store_name'))->rules('required');
                switch (Admin::user()) {
                     case 'Administrator':
                        $form->select('wid', trans('admin::lang.location_area'))->options(
                            Warehouse::pluck('w_name','wid')->toArray())->rules('required');
                        break;
                    default:
                        $form->hidden('wid',trans('admin::lang.wid'))->value(Admin::user()->wid); 
                }
                $form->select('city_id', trans('admin::lang.city_id'))->options(
                    WebArea::City()->pluck('area_name', 'id')->toArray()
                )->load('district_id', '/admin/api/tw/district')->rules('required');
                $form->select('district_id', trans('admin::lang.district_id'))->options(function ($id) {
                    return WebArea::options($id);
                })->rules('required');
                $form->text('store_address', trans('admin::lang.store_address'))->rules('required');

                $form->divide();
                $form->dateRange('store_lease_start', 'store_lease_end', trans('admin::lang.store_lease_start_end'));
                $form->date('store_payment_date', trans('admin::lang.store_payment_date'))->format('YYYY-MM-DD');
                $form->currency('store_rents', trans('admin::lang.store_rents'))->symbol('$')->options(['mask' => '']);
                $form->currency('store_deposit', trans('admin::lang.store_deposit'))->symbol('$')->options(['mask' => '']);
                $form->text('store_contractor', trans('admin::lang.store_contractor'));
                $states = [
                    'on'  => ['value' => 1, 'text' => '開店', 'color' => 'success'],
                    'off' => ['value' => 0, 'text' => '閉店', 'color' => 'danger'],
                ];

                $form->switch('store_status', trans('admin::lang.status'))->states($states);

            })->tab('網頁顯示', function ($form) {

                // $form->editor('store_map',trans('admin::lang.store_map'))->help('<a href="https://goo.gl/13yFtr">幫助</a>');
                $form->image('store_picture', trans('admin::lang.store_pic'))->move('/location','store_pic');
                $states = [
                    'on'  => ['value' => 1, 'text' => 'ON', 'color' => 'success'],
                    'off' => ['value' => 0, 'text' => 'OFF', 'color' => 'danger'],
                ];
                $form->switch('show',trans('admin::lang.showfront'))->states($states);
                $form->textarea('comment',trans('admin::lang.comment'))->rows(10);
                $form->display('created_at',trans('admin::lang.created_at'));
                $form->display('updated_at',trans('admin::lang.updated_at'));
            });

            $form->saving(function (Form $form) {

            });

        });
    }

    public function district(Request $request)
    {
        $cityId = $request->get('q');
        return WebArea::District()->where('parent_id', $cityId)->get(['id', DB::raw('area_name as text')]);
    }
}


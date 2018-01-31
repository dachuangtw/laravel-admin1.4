<?php

namespace App\Admin\Controllers;

use App\WebLocation;
use App\WebArea;
use App\Sales;
use App\Location;

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
        Permission::check(['WebLocation-Reader','WebLocation-Editor','WebLocation-Creator','WebLocation-Deleter']);
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
        Permission::check(['WebLocation-Reader','WebLocation-Editor','WebLocation-Deleter']);
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
        Permission::check(['WebLocation-Reader','WebLocation-Creator','WebLocation-Deleter']);
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
                // sql: ... WHERE `user.name` LIKE "%$name%";
                $filter->like('store_name', trans('admin::lang.store_name'));
            });
            $grid->model()->orderBy('location_id', 'DESC');
            $grid->location_id(trans('admin::lang.store_id'))->sortable();
            // $grid->city_id(trans('admin::lang.city_id'))->sortable()->display(function($city_id){
            //        return WebArea::find($city_id)->area_name;
            // });
            $grid->store_name(trans('admin::lang.store_name'));
            // $grid->created_at(trans('admin::lang.created_at'));
            // $grid->updated_at(trans('admin::lang.updated_at'));
            $states = [
                'on'  => ['value' => 1, 'text' => 'ON', 'color' => 'success'],
                'off' => ['value' => 2, 'text' => 'OFF', 'color' => 'danger'],
            ];
            $grid->column('show',trans('admin::lang.showfront'))->status()->switch($states);
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

                $form->display('location_id', trans('admin::lang.store_id'));
                $form->text('store_name', trans('admin::lang.store_name'))->rules('required');
                $form->select('area_id', trans('admin::lang.location_area'))
                ->options(
                    DB::table('warehouse')->pluck('w_name','wid')->toArray()
                )->rules('required');
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
                // $form->select('sales', trans('admin::lang.store_sales'))
                // ->options(Sales::all()->pluck('sales_name','sid'));

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


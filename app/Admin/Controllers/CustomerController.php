<?php

namespace App\Admin\Controllers;
use Encore\Admin\Auth\Permission;
use App\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Tab;
use Encore\Admin\Widgets\Table;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;


class CustomerController extends Controller
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

            $content->header(trans('admin::lang.customer'));
            $content->description(trans('admin::lang.list'));
            $grid=Admin::grid(User::class, function (Grid $grid) {
                $grid->id('ID')->sortable();
                $grid->name(trans('admin::lang.name'));
                $grid->email(trans('admin::lang.email'));
                $grid->phone(trans('admin::lang.phone'));
    
                $grid->filter(function ($filter) {
                    $filter->is('name');
                    $filter->is('email');
                    $filter->is('phone');
                    $filter->useModal();
                });
                
            });           
            $content->body($grid);
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
            $content->header(trans('admin::lang.user'));
            $content->description(trans('admin::lang.edit'));
            $content->breadcrumb(
                ['text' => trans('admin::lang.product_index'), 'url' => '/product'],
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
        return Admin::content(function (Content $content) {

            $content->header(trans('admin::lang.user'));
            $content->description(trans('admin::lang.create'));
            $content->body($this->form());
        });
    }



    /**
     * View interface.
     *
     * @return Content
     */
    public function view($id)
    {
      
        $user = User::find($id)->toArray();

        //忽略不顯示的欄位
        $skipArray = ['id','password','created_at','updated_at','login_at'];
        $showArray = ['name','gender','birthday','email','phone','line','postal_code','address' ];
        $header[] = '會員資訊';
        foreach($user as $key => $value){       
            if(in_array($key,$skipArray) || empty($value)){
                if(!(isset($showArray) && Admin::user()->inRoles($showArray))){
                    continue;
                } 
            }
            $newkey = trans('admin::lang.'.$key);            
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

        $table = new Table($header, $rows);
        $table->class('table table-hover');
        return $table->render();
    }


    
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(User::class, function (Form $form) {
            $form->tab('基本資料', function (Form $form) {
                $form->text('name', trans('admin::lang.user'))->rules('required');
                $form->date('birthday', trans('admin::lang.birthday'))->rules('required');
                $form->radio('gender', trans('admin::lang.gender'))
                ->options(['男' =>'男','女' =>'女','其他' =>'其他','不願透露' =>'不願透露']);
                $form->email('email', trans('admin::lang.username'))->rules('required');
                $form->password('password', trans('admin::lang.password'))->rules('required');
            })->tab('聯絡資訊', function (Form $form) {
                $form->text('phone', trans('admin::lang.phone'))->rules('required');
                $form->text('line', trans('admin::lang.line'));
                $form->text('postal_code', trans('admin::lang.postal_code'));
                $form->text('address', trans('admin::lang.address'));
                //備註 $form->textarea('remarks',trans('admin::lang.notes'));

            })->tab('帳號資訊', function (Form $form) {
                $form->display('client_ip',trans('admin::lang.client_ip'));
                $form->display('client_agent',trans('admin::lang.client_agent'));
                $form->display('logged_in_at',trans('admin::lang.logged_in_at'));
                $form->divide();
                $form->date('created_at', trans('admin::lang.created_at'));
                $form->date('updated_at', trans('admin::lang.updated_at'));
            });
            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
            });
    
        });
    } 
  
}

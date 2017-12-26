<?php

namespace App;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class ProductSeries extends Model
{
    use ModelTree, AdminBuilder;
    //主題系列資料表
    protected $table = 'product_series';
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setParentColumn('parent_id');
        $this->setOrderColumn('ps_sort');
        $this->setTitleColumn('ps_name');
    }

    //主鍵
    protected $primaryKey = 'psid';

    //需要被轉換成日期的屬性
    protected $dates = ['deleted_at'];

    //批量賦值
    protected $fillable = [
        'ps_name', 'ps_sort', 'showfront', 'update_user', 'updated_at'
    ];

    //前台顯示
    public function scopeShowfront($query)
    {
        return $query->where('showfront', 1);
    }
}

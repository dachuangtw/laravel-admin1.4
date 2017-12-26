<?php

namespace App;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use ModelTree, AdminBuilder;

    //倉庫資料表
    protected $table = 'warehouse';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setParentColumn('parent_id');
        $this->setOrderColumn('w_sort');
        $this->setTitleColumn('w_name');
    }

    //主鍵
    protected $primaryKey = 'wid';

    //需要被轉換成日期的屬性
    protected $dates = ['deleted_at'];

    //批量賦值
    protected $fillable = [
        'w_name', 'w_sort', 'showfront', 'created_at', 'updated_at'
    ];

    //前台顯示
    public function scopeShowfront($query)
    {
        return $query->where('showfront', 1);
    }

}

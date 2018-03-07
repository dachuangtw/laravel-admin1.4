<?php

namespace App;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use ModelTree, AdminBuilder;

    //DB2
    protected $connection = 'mysql2';
    //倉庫資料表
    // protected $table = 'warehouse';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setParentColumn('parent_id');
        $this->setOrderColumn('sort');
        $this->setTitleColumn('name');
    }

    //主鍵
    // protected $primaryKey = 'wid';

    //需要被轉換成日期的屬性
    // protected $dates = ['deleted_at'];

    //批量賦值
    // protected $fillable = [
    //     'name', 'sort', 'showfront', 'created_at', 'updated_at'
    // ];
	protected $fillable = [
        'name', 'sort', 'created_at', 'updated_at'
    ];

    //前台顯示
    // public function scopeShowfront($query)
    // {
    //     return $query->where('showfront', 1);
    // }

    //前台顯示
    public function scopeOfWarehouse($query,$wid)
    {
        return $query->where('id', $wid)->get();
    }
}

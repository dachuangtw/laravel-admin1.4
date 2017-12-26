<?php

namespace App;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use ModelTree, AdminBuilder;

    //商品分類資料表
    protected $table = 'product_category';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setParentColumn('parent_id');
        $this->setOrderColumn('pc_sort');
        $this->setTitleColumn('pc_name');
    }

    //主鍵
    protected $primaryKey = 'pcid';

    //需要被轉換成日期的屬性
    protected $dates = ['deleted_at'];

    //批量賦值
    protected $fillable = [
        'pc_name', 'pc_sort', 'showfront', 'update_user', 'updated_at'
    ];

    //前台顯示
    public function scopeShowfront($query)
    {
        return $query->where('showfront', 1);
    }
}

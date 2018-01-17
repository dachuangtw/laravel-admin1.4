<?php

namespace App;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class ProductSupplier extends Model
{    
    use ModelTree, AdminBuilder;
    //廠商資料表
    protected $table = 'product_supplier';
    //主鍵
    protected $primaryKey = 'supid';
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setParentColumn('parent_id');
        $this->setOrderColumn('sup_sort');
        $this->setTitleColumn('sup_name');
    }

    //前台顯示
    public function scopeShowfront($query)
    {
        return $query->where('showfront', 1);
    }
    //前台顯示
    public function scopeOfSupplier($query,$supid)
    {
        return $query->where('supid', $supid)->get();
    }
}

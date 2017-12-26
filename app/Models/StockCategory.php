<?php

namespace App;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class StockCategory extends Model
{
    use ModelTree, AdminBuilder;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setParentColumn('parent_id');
        $this->setOrderColumn('sc_sort');
        $this->setTitleColumn('sc_name');
    }

    //庫存資料表
    protected $table = 'stock_category';
    //主鍵
    protected $primaryKey = 'scid';

}

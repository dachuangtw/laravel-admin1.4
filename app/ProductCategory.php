<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    //商品分類資料表
    protected $table = 'product_category';
    //主鍵
    protected $primaryKey = 'pcid';
}

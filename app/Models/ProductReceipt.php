<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductReceipt extends Model
{
    //商品主檔資料表
    protected $table = 'product_receipt';
    //主鍵
    protected $primaryKey = 'reid';

    //需要被轉換成日期的屬性
    protected $dates = ['deleted_at'];

    //批量賦值
    protected $fillable = [
        'update_user',  'updated_at', 'deleted_at'
    ];

}

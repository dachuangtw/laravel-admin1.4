<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductReceiptDetails extends Model
{
    //資料update跟create時不自動儲存
    public $timestamps = false;
    
    //進貨單明細資料表
    protected $table = 'product_receipt_details';
    //主鍵
    protected $primaryKey = 'redid';

    //批量賦值
    protected $fillable = [
        're_number', 'pid', 'stid',  'red_quantity', 'red_price',  'red_amount', 'red_notes'
    ];

    //進貨單號
    public function scopeOfselected($query, $re_number)
    {
        return $query->where('re_number', $re_number)->get();
    }
}

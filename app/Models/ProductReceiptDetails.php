<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductReceiptDetails extends Model
{
    //進貨單明細資料表
    protected $table = 'product_receipt_details';
    //主鍵
    protected $primaryKey = 'redid';

    //需要被轉換成日期的屬性
    protected $dates = ['deleted_at'];

    //批量賦值
    protected $fillable = [
        're_number', 'pid',  'red_quantity', 'red_price',  'red_amount', 'red_notes'
    ];

    //進貨單號
    public function scopeOfselected($query, $re_number)
    {
        return $query->where('re_number', $re_number)->get();
    }
    
    //一(商品)對多(庫存)關聯資料表
    // public function stock()
    // {
    //     return $this->belongsTo(Stock::class,'pid');
    // }
}

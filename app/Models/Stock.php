<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    //使用軟刪除
    use SoftDeletes;

    //庫存資料表
    protected $table = 'stock';
    //主鍵
    protected $primaryKey = 'stid';
    //需要被轉換成日期的屬性
    protected $dates = ['deleted_at'];

    //批量賦值
    protected $fillable = [
        'pid',  'wid',  'st_type',  'st_barcode',  'st_stock',  'st_collect', 'st_notes', 'st_unit', 'showfront',  
        'update_user',  'updated_at', 'deleted_at'
    ];

    //多(庫存)對一(商品)關聯資料表
    public function productindex()
    {
        return $this->belongsTo(ProductIndex::class,'pid');
    }
}

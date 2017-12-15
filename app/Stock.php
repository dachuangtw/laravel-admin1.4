<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    //庫存資料表
    protected $table = 'stock';
    //主鍵
    protected $primaryKey = 'sid';

    //批量賦值
    protected $fillable = [
        'update_user',  'updated_at', 'deleted_at'
    ];

    //多(庫存)對一(商品)關聯資料表
    public function productindex()
    {
        return $this->belongsTo(ProductIndex::class,'pid');
    }
}

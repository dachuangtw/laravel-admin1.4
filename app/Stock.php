<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    //庫存資料表
    protected $table = 'stock';
    //主鍵
    protected $primaryKey = 'sid';

    public function productindex()
    {
        return $this->belongsTo(ProductIndex::class,'pid');
    }
}

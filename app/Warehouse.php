<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //倉庫資料表
    protected $table = 'warehouse';
    //主鍵
    protected $primaryKey = 'wid';

    //需要被轉換成日期的屬性
    protected $dates = ['deleted_at'];

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    //使用軟刪除
    use SoftDeletes;
   
    //調撥單資料表
    protected $table = 'inventory';
    //主鍵
    protected $primaryKey = 'inid';
    //需要被轉換成日期的屬性
    protected $dates = ['deleted_at'];
}

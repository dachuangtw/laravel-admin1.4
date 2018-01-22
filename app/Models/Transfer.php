<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transfer extends Model
{
    //使用軟刪除
    use SoftDeletes;
   
    //調撥單資料表
    protected $table = 'transfer';
    //主鍵
    protected $primaryKey = 'tid';
    //需要被轉換成日期的屬性
    protected $dates = ['deleted_at'];

}

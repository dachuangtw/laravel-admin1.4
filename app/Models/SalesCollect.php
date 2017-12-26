<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesCollect extends Model
{
    //領貨資料表
    protected $table = 'sales_collect';
    //主鍵
    protected $primaryKey = 'soid';
}

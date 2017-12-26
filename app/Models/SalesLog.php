<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesLog extends Model
{
    //業務紀錄資料表
    protected $table = 'sales_log';
    //主鍵
    protected $primaryKey = 'slid';
}

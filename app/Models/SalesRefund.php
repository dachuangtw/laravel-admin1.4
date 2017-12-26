<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesRefund extends Model
{
    //業務退貨資料表
    protected $table = 'sales_refund';
    //主鍵
    protected $primaryKey = 'srid';
}

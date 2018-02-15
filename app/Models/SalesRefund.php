<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesRefund extends Model
{
    //使用軟刪除
    use SoftDeletes;
    //業務退貨資料表
    protected $table = 'sales_refund';
    //主鍵
    protected $primaryKey = 'srid';

    protected $dates = ['deleted_at'];

    //批量賦值
    protected $fillable = [
        'update_user',  'updated_at', 'deleted_at'
    ];
    
}

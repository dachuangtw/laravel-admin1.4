<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    //使用軟刪除
    use SoftDeletes;
    //DB2
    protected $connection = 'mysql2';
    //業務資料表
    protected $table = 'sales';
    //主鍵
    // protected $primaryKey = 'sales_id';

    protected $fillable = [
        'name', 'account', 'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    //需要被轉換成日期的屬性。
    protected $dates = ['deleted_at'];

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebLocation extends Model
{
    //使用軟刪除
    use SoftDeletes;
    //DB2
    protected $connection = 'mysql2';
    //店鋪據點資料表
    protected $table = 'stores';
    //主鍵
    // protected $primaryKey = 'location_id';
    //軟刪除
    protected $dates = ['deleted_at'];

 }

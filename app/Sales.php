<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    //業務資料表
    protected $table = 'sales';
    //主鍵
    protected $primaryKey = 'sid';
}

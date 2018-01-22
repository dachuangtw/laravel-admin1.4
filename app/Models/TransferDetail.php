<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferDetail extends Model
{
    //調撥單資料表
    protected $table = 'transferdetial';
    //主鍵
    protected $primaryKey = 'tdid';
}

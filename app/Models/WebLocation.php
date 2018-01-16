<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebLocation extends Model
{
    //DB2
    protected $connection = 'mysql2';
    //店鋪據點資料表
    protected $table = 'location';
    //主鍵
    protected $primaryKey = 'location_id';

 }

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //DB2
    protected $connection = 'mysql2';
    //地區資料表
    protected $table = 'city';
    //主鍵
    protected $primaryKey = 'city_id';  

}

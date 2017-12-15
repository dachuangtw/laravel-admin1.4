<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebLocation extends Model
{
    //店鋪據點資料表
    protected $table = 'web_location';
    //主鍵
    protected $primaryKey = 'id';

    // public function web_area()
    // {
    //     return $this->belongsTo(WebArea::class);
    // }
}

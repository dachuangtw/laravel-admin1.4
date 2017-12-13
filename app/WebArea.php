<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebArea extends Model
{
    //地區資料表
    protected $table = 'web_area';
    //主鍵
    protected $primaryKey = 'id';

    public function selectoptions(){
        $optionArray = [];
        $dbArray = $this->toArray();

        return $dbArray;
    }
}

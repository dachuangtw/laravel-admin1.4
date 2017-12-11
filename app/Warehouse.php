<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //
    //商品主檔資料表
    protected $table = 'warehouse';
    //主鍵
    protected $primaryKey = 'wid';

    //需要被轉換成日期的屬性
    protected $dates = ['deleted_at'];

    public function selectoptions(){
        $optionArray = [];
        $dbArray = $this->toArray();
        // foreach ($dbArray as $option) {
        //     $optionArray[$dbArray[wid]] = $dbArray[w_name];
        // }
        return $dbArray;
        //return 1;
    }
}

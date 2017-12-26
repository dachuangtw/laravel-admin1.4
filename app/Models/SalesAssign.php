<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesAssign extends Model
{
    //每日配貨資料表
    protected $table = 'sales_assign';
    //主鍵
    protected $primaryKey = 'said';

    //關聯AssignDetails
    public function SalesAssignDetails()
    {
        $this->hasOne(SalesAssignDetails::class);
    }
}

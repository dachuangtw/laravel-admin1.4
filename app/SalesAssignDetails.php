<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesAssignDetails extends Model
{
    //配貨明細表資料表
    protected $table = 'sales_assign_details';
    //主鍵
    protected $primaryKey = 'said';

    //關聯 SalesAssign
    public function SalesAssign()
    {
        $this->belongsTo(SalesAssign::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesAssignDetails extends Model
{
    //配貨明細表資料表
    protected $table = 'sales_assign_details';
    //主鍵
    protected $primaryKey = 'said';

    protected $fillable = ['pid','s_type', 'p_salesprice','p_quantity','p_salesprice_total','created_at'];

    //關聯 SalesAssign
    public function SalesAssign()
    {
       return $this->belongsTo(SalesAssign::class,'assign_id');
    }
}

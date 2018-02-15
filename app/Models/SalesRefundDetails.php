<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesRefundDetails extends Model
{
    //資料update跟create時不自動儲存
    public $timestamps = false;
    
    //領貨明細表資料表
    protected $table = 'sales_refund_details';
    //主鍵
    protected $primaryKey = 'srdid';

    protected $fillable = ['refund_id','pid','stid','srd_salesprice','srd_quantity','srd_amount','srd_check','srd_notes'];
    
    //領貨單號
    public function scopeOfselected($query, $refund_id)
    {
        return $query->where('refund_id', $refund_id)->get();
    }


}

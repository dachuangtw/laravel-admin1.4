<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesCollectDetails extends Model
{
    //資料update跟create時不自動儲存
    public $timestamps = false;
    
    //領貨明細表資料表
    protected $table = 'sales_collect_details';
    //主鍵
    protected $primaryKey = 'scdid';

    protected $fillable = ['collect_id','pid','stid','scd_salesprice','scd_quantity','scd_amount','scd_check','scd_notes'];
    
    //領貨單號
    public function scopeOfselected($query, $collect_id)
    {
        return $query->where('collect_id', $collect_id)->get();
    }


}

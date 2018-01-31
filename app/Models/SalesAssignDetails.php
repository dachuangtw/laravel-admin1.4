<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesAssignDetails extends Model
{
    //資料update跟create時不自動儲存
    public $timestamps = false;
    
    //配貨明細表資料表
    protected $table = 'sales_assign_details';
    //主鍵
    protected $primaryKey = 'sadid';

    protected $fillable = ['assign_id','pid','stid','sad_salesprice','sad_quantity','sad_amount'];
    
    //配貨單號
    public function scopeOfselected($query, $assign_id)
    {
        return $query->where('assign_id', $assign_id)->get();
    }


}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferDetail extends Model
{
    //資料update跟create時不自動儲存
    public $timestamps = false;

    //調撥單資料表
    protected $table = 'transfer_detail';
    //主鍵
    protected $primaryKey = 'tdid';
    
    //批量賦值
    protected $fillable = [
        't_number', 'pid', 'stid',  'td_quantity', 'td_price',  'td_amount', 'td_notes'
    ];

    //進貨單號
    public function scopeOfselected($query, $t_number)
    {
        return $query->where('t_number', $t_number)->get();
    }
}

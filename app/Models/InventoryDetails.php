<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryDetails extends Model
{
    //資料update跟create時不自動儲存
    public $timestamps = false;

    //盤點單明細資料表
    protected $table = 'inventory_details';
    //主鍵
    protected $primaryKey = 'indid';
    
    //批量賦值
    protected $fillable = [
        'in_number', 'pid', 'stid', 'in_quantity', 'ind_stock',  'ind_difference', 'ind_notes', 'ind_user', 'update_user', 'ind_at', 'created_at', 'updated_at'
    ];    
    
    //用盤點單號取得明細資料
    public function scopeOfselected($query, $in_number)
    {
        return $query->where('in_number', $in_number)->get();
    }
}

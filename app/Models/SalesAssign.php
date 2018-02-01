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
       return $this->hasMany(SalesAssignDetails::class,'assign_id','assign_id');
    }

    //刪除配貨單同時刪除配貨明細
    protected static function boot()
    {
        parent::boot();
        static::deleting(function($details) {
            $details->SalesAssignDetails()->delete();
        });
    }
}

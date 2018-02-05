<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesCollect extends Model
{
    //使用軟刪除
    use SoftDeletes;
    //領貨資料表
    protected $table = 'sales_collect';
    //主鍵
    protected $primaryKey = 'scid';
        
    protected $dates = ['deleted_at'];

    //批量賦值
    protected $fillable = [
        'update_user',  'updated_at', 'deleted_at'
    ];
    
    // 關聯CollectDetails
    public function SalesCollectDetails()
    {
       return $this->hasMany(SalesCollectDetails::class,'collect_id','collect_id');
    }
    
    // 刪除領貨單同時刪除領貨明細
    protected static function boot()
    {
        parent::boot();
        static::deleting(function($details) {
            // $details->SalesCollectDetails()->delete();
        });
    }
}

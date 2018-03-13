<?php

namespace App;
use Encore\Admin\Facades\Admin;
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
    
    // 刪除領貨單同時刪除領貨明細 新增log紀錄
    protected static function boot()
    {
        parent::boot();
        static::deleted(function($model) {
            // $details->SalesCollectDetails()->delete();
            $SalesCollectDetails = SalesCollectDetails::where('collect_id',$model->collect_id)->pluck('scd_quantity','stid');

            $insertStockLogArray = [];
            foreach($SalesCollectDetails as $stid => $quantity){
                $stock = Stock::where('stid',$stid)->select('pid', 'wid','st_stock')->first();

                $st_stock = (int) $stock->st_stock + (int) $quantity;
                Stock::find($stid)->update(['st_stock' => $st_stock]);

                $insertStockLogArray[] = [
                    'pid'          =>  $stock->pid,
                    'wid'          =>  $stock->wid,
                    'stid'         =>  $stid,
                    'sl_calc'      =>  '+',
                    'sl_quantity'  =>  $quantity,
                    'sl_stock'     =>  $st_stock,
                    'sl_notes'     =>  '領貨單：'.$model->collect_id.'-刪除',
                    'update_user'  =>  Admin::user()->id,
                    'updated_at'    =>  date('Y-m-d H:i:s'),
                ];
            }
            $insertStockLogArray && StockLog::insert($insertStockLogArray);
        });
    }
}

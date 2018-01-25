<?php

namespace App;

use Encore\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReceipt extends Model
{
    //使用軟刪除
    use SoftDeletes;

    //進貨單資料表
    protected $table = 'product_receipt';
    //主鍵
    protected $primaryKey = 'reid';

    //需要被轉換成日期的屬性
    protected $dates = ['deleted_at'];

    //批量賦值
    protected $fillable = [
        'update_user',  'updated_at', 'deleted_at'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {

            $ProductReceiptDetails = ProductReceiptDetails::where('re_number',$model->re_number)->pluck('red_quantity','stid');

            $insertStockLogArray = [];
            foreach($ProductReceiptDetails as $stid => $quantity){
                $stock = Stock::where('stid',$stid)->select('pid', 'wid','st_stock')->first();

                $st_stock = (int) $stock->st_stock - (int) $quantity;
                Stock::find($stid)->update(['st_stock' => $st_stock]);

                $insertStockLogArray[] = [
                    'pid'          =>  $stock->pid,
                    'wid'          =>  $stock->wid,
                    'stid'         =>  $stid,
                    'sl_calc'      =>  '-',
                    'sl_quantity'  =>  $quantity,
                    'sl_stock'     =>  $st_stock,
                    'sl_notes'     =>  '進貨單：'.$model->re_number.'-刪除',
                    'update_user'  =>  Admin::user()->id,
                    'update_at'    =>  date('Y-m-d H:i:s'),
                ];
            }
            $insertStockLogArray && StockLog::insert($insertStockLogArray);

            // ProductReceipt進貨單使用軟刪除，所以訂貨明細不用刪，把庫存調整回來即可。
            // ProductReceiptDetails::where('re_number',$model->re_number)->delete();
        });
    }
}

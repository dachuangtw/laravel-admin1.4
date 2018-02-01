<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Encore\Admin\Facades\Admin;

class ProductIndex extends Model
{
    //使用軟刪除
    use SoftDeletes;

    //商品主檔資料表
    protected $table = 'product_index';
    //主鍵
    protected $primaryKey = 'pid';

    //需要被轉換成日期的屬性
    protected $dates = ['deleted_at'];

    //批量賦值
    protected $fillable = [
        'p_number', 'p_name', 'p_salesprice', 'p_costprice', 'update_user',  'updated_at', 'deleted_at'
    ];

    /**
     * 單一function無法在Controller的$grid使用2次(會出錯)，
     * 使用一般的資料表欄位名2次一樣會出錯，應該是模板本身的bug。
     * 無奈只能多幾個function給$grid使用。
     */
    //一(商品)對多(庫存)關聯資料表
    public function stock()
    {
        return $this->hasMany(Stock::class,'pid')->where('wid', Admin::user()->wid)->orWhere('wid', '2');
    }
    public function stock1()
    {
        return $this->hasMany(Stock::class,'pid');
    }
    public function stock2()
    {
        return $this->hasMany(Stock::class,'pid');
    }
    public function stock3()
    {
        return $this->hasMany(Stock::class,'pid');
    }
    public function stock4()
    {
        return $this->hasMany(Stock::class,'pid');
    }

    //前台顯示
    // public function scopeShowfront($query)
    // {
    //     return $query->where('showfront', 1);
    // }

    //限制分類
    public function scopeOfCategory($query, $type)
    {
        return $query->where('p_category', $type);
    }

    //限制主題系列
    // public function scopeOfSeries($query, $type)
    // {
    //     return $query->where('p_series', $type);
    // }

    //商品副圖(多圖用|分隔)
    public function setPImagesAttribute($pictures)
    {
        if (is_array($pictures)) {
            $this->attributes['p_images'] = implode('|',$pictures);
        }
    }

    public function getPImagesAttribute($pictures)
    {
        if (is_string($pictures)) {
            return explode('|',$pictures);
        }
        return $pictures;
    }

    //主題系列勾選(用|分隔)
    public function setPSeriesAttribute($series)
    {
        if (is_array($series)) {
            $this->attributes['p_series'] = implode('|',$series);
        }
    }

    public function getPSeriesAttribute($series)
    {
        if (is_string($series)) {
            return explode('|',$series);
        }
        return $series;
    }

    //商品分類多選框(用|分隔)
    public function setPCategoryAttribute($category)
    {
        if (is_array($category)) {
            $this->attributes['p_category'] = implode('|',$category);
        }
    }

    public function getPCategoryAttribute($category)
    {
        if (is_string($category)) {
            return explode('|',$category);
        }
        return $category;
    }
}

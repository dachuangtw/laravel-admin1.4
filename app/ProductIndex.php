<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'p_category', 'p_series', 'p_notes', 'showfront', 'update_user',  'updated_at', 'deleted_at'
    ];

    //前台顯示
    public function scopeShowfront($query)
    {
        return $query->where('showfront', 1);
    }
    
    //限制分類
    public function scopeOfCategory($query, $type)
    {
        return $query->where('p_category', $type);
    }

    //限制主題系列
    public function scopeOfSeries($query, $type)
    {
        return $query->where('p_series', $type);
    }

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
}

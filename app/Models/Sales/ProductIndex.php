<?php

namespace App\Models\sales;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;

class ProductIndex extends Model
{
	protected $table = 'product_index';

	protected $primaryKey = 'pid';

	// 範圍，前台顯示&新品排序
	public function scopeShow($query)
	{
		return $query->where('showfront', 1)
			->orderBy('shownew', 'desc');
	}

	// 範圍，可領貨
	public function scopeShowSales($query)
	{
		return $query->where('showsales', 1);
	}

	// 範圍，商品分類(id = 0 為'未分類')
	public function scopeOfCategory($query, $p_category)
    {
		if ((int)$p_category == 0) {
			return $query->whereNull('p_category');

		} else {
			return $query->where('p_category',  $p_category)
			->orWhere('p_category', 'like', '%'.$p_category.'|')
			->orWhere('p_category', 'like', $p_category.'|%')
			->orWhere('p_category', 'like', '%|'.$p_category.'|%')
			->orWhere('p_category', '-1');
		}
    }

    // 取得副圖
	public function getPImagesAttribute($pictures)
    {
        if (is_string($pictures)) {
            return explode('|',$pictures);
        }
        return $pictures;
    }

    // 取得主題系列
	public function getPSeriesAttribute($series)
    {
        if (is_string($series)) {
            return explode('|',$series);
        }
        return $series;
    }

    // 取得商品分類
	public function getPCategoryAttribute($category)
    {
        if (is_string($category)) {
            return explode('|',$category);
        }
        return $category;
    }

	// 一對多關聯，商品庫存
	public function hasManyStock()
	{
		return $this->hasMany('App\Models\Sales\Stock', 'pid')->where('wid', Auth::user()->wid);
	}
}

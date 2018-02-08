<?php

namespace App\Models\sales;

use Illuminate\Database\Eloquent\Model;

class ProductIndex extends Model
{
	protected $table = 'product_index';
	protected $primaryKey = 'pid';

	// 範圍，前台顯示
	public function scopeShow($query)
	{
		return $query->where('showfront', '1')
			->orderBy('shownew', 'desc');
	}

	// 範圍，商品分類
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

}

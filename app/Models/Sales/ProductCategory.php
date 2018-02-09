<?php

namespace App\Models\sales;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
	protected $table = 'product_category';

	protected $primaryKey = 'pcid';

	// 範圍，前台顯示
	public function scopeShow($query)
	{
		return $query->where('showfront',  1)
			->orderBy('pc_sort', 'asc');
	}
}

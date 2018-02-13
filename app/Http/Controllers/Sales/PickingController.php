<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Cart as Cart;

use App\Models\sales\ProductCategory;
use App\Models\sales\ProductIndex;

class PickingController extends Controller
{
    // 全商品顯示
	public function index(Request $request)
	{
		return $this->display($request->user()->limited_time);
	}

	/**
	 *    依分類顯示
	 *    @param  int  $id      類別ID
	 */
	public function categories(Request $request, $id)
	{
		return $this->display($request->user()->limited_time, $id);
	}

	/**
	 *    顯示商品
	 *    @param  datetime $limited_time  領貨結束時間
	 *    @param  int|null $category_id 商品分類ID
	 */
	public function display($limited_time, $category_id = NULL)
	{
        // 剩餘領貨時間
		$picking_time = $this->limitedTime($limited_time);

		if ($category_id == NULL) {
            // 全部商品
			$products = ProductIndex::show()
			->paginate(12);

			return view('sales.picking', [
				'categories' => ProductCategory::show()->get(),
				'products' => $products,
				'picking_time' => $picking_time,
			]);

		} else {
			$products = ProductIndex::show()
			->ofCategory($category_id)
			->paginate(12);

			return view('sales.picking', [
				'categories' => ProductCategory::show()->get(),
				'category_id' => $category_id,
				'products' => $products,
				'picking_time' => $picking_time,
			]);
		}
	}

	/**
	 *    計算領貨剩餘時間
	 *    @param  datetime $limited_time 領貨結束時間
	 */
	public function limitedTime($limited_time)
	{
		$sec = strtotime($limited_time) - time();
		$d = floor($sec / (24*60*60));
		$H = floor(($sec % (24*60*60)) / (60*60));
		$i = floor((($sec % (24*60*60)) % (60*60)) / 60);
		$s = floor((($sec % (24*60*60)) % (60*60)) % 60);

		return ['d' => $d, 'H' => $H, 'i' => $i, 's' => $s];
	}

	/**
	 *    商品詳細
	 *    @param  string  $id      商品編號
	 */
	public function detail(Request $request, $number)
	{
		$product = ProductIndex::where('p_number', $number)->first();
		if ($product) {
			return view('sales.picking-detail', [
				'product' => $product,
			]);

		} else {
			return abort(404);
		}
	}
}

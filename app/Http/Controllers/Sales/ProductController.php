<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// use \Cart as Cart;
use App\Models\sales\ProductCategory;
use App\Models\sales\ProductIndex;

class ProductController extends Controller
{
	public function index(Request $request)
	{
		return $this->display($request->user()->collect_at);
	}

	/**
	 *    依分類顯示
	 *    @param  int  $id      類別ID
	 */
	public function categories(Request $request, $id)
	{
		return $this->display($request->user()->collect_at, $id);
	}

	/**
	 *    顯示商品瀏覽頁
	 *    @param  datetime $collect_at  領貨結束時間
	 *    @param  int|null $category_id 商品分類ID
	 */
	public function display($collect_at, $category_id = NULL)
	{
		$categories = ProductCategory::show()->get();

		if ($category_id == NULL) {
			$products = ProductIndex::show()
			->paginate(9);
		} else {
			$products = ProductIndex::show()
			->ofCategory($category_id)
			->paginate(9);
		}

		$picking_time = $this->pickingTime($collect_at);

		if ($picking_time) {
			return view('sales.product', [
				'picking' => TRUE,
				'picking_time' => $picking_time,
				'categories' => $categories,
				'category_id' => $category_id,
				'products' => $products,
			]);

		} else {
            // 商品瀏覽
			return view('sales.product', [
				'picking' => FALSE,
				'categories' => $categories,
				'category_id' => $category_id,
				'products' => $products,
			]);
		}
	}

	/**
	 *    計算領貨時間，非領貨時間回傳FALSE
	 *    @param  datetime $collect_at 領貨結束時間
	 */
	public function pickingTime($collect_at)
	{
		$collect_at = ($collect_at) ?: 'now';

		if (strtotime($collect_at) > time()) {
			// 還有領貨時間，顯示領貨功能
			$sec = strtotime($collect_at) - time();
			$d = floor($sec / (24*60*60));
			$H = floor(($sec % (24*60*60)) / (60*60));
			$i = floor((($sec % (24*60*60)) % (60*60)) / 60);
			$s = floor((($sec % (24*60*60)) % (60*60)) % 60);

			return ['d' => $d, 'H' => $H, 'i' => $i, 's' => $s];
		} else {
			return FALSE;
		}
	}

	public function detail(Request $request)
	{
		if (strtotime($request->user()->collect_at) > time()) {
			// 還有領貨時間，顯示領貨功能
			return view('sales.product-detail', [
				'picking' => TRUE,
			]);

		} else {
            // 商品瀏覽
			return view('sales.product-detail', [
				'picking' => FALSE,
			]);
		}

	}
}

<?php

namespace App\Http\Controllers\Sales;

use App\Models\sales\ProductCategory;
use App\Models\sales\ProductIndex;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
	public function index(Request $request)
	{
		$categories = ProductCategory::show()->get();
		$products = ProductIndex::show()->get();

		if (strtotime($request->user()->collect_at) > time()) {
			// 還有領貨時間，顯示領貨功能
			$sec = strtotime($request->user()->collect_at) - time();
			$d = floor($sec / (24*60*60));
			$H = floor(($sec % (24*60*60)) / (60*60));
			$i = floor((($sec % (24*60*60)) % (60*60)) / 60);
			$s = floor((($sec % (24*60*60)) % (60*60)) % 60);


			return view('sales.product', [
				'picking' => TRUE,
				'days' => $d,
				'hours' => $H,
				'minutes' => $i,
				'seconds' => $s,
				'categories' => $categories,
				'products' => $products,
			]);

		} else {
            // 商品瀏覽
			return view('sales.product', [
				'picking' => FALSE,
				'categories' => $categories,
				'products' => $products,
			]);
		}

	}

	/**
	 *    依分類顯示
	 *    @param  Request $request
	 *    @param  int  $id      類別ID
	 */
	public function categories(Request $request, $id)
	{
		$categories = ProductCategory::show()->get();
		$products = ProductIndex::show()->ofCategory($id)->get();

		if (strtotime($request->user()->collect_at) > time()) {
			// 還有領貨時間，顯示領貨功能
			$sec = strtotime($request->user()->collect_at) - time();
			$d = floor($sec / (24*60*60));
			$H = floor(($sec % (24*60*60)) / (60*60));
			$i = floor((($sec % (24*60*60)) % (60*60)) / 60);
			$s = floor((($sec % (24*60*60)) % (60*60)) % 60);


			return view('sales.product', [
				'picking' => TRUE,
				'days' => $d,
				'hours' => $H,
				'minutes' => $i,
				'seconds' => $s,
				'categories' => $categories,
				'categories_id' => $id,
				'products' => $products,
			]);

		} else {
            // 商品瀏覽
			return view('sales.product', [
				'picking' => FALSE,
				'categories' => $categories,
				'categories_id' => $id,
				'products' => $products,
			]);
		}
	}
}

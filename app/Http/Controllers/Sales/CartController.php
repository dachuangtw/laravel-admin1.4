<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Cart as Cart;

use App\Models\sales\ProductIndex;

class CartController extends Controller
{
    // 顯示購物車
	public function index(Request $request)
	{
		return view('sales.cart', [
			'picking_time' => ProductIndex::limitedTime($request->user()->limited_time),
			'cart_content' => Cart::content(),
			'cart_count' => Cart::count(),
			'cart_subtotal' => Cart::subtotal(0),
		]);
	}

    // 新增
	public function add(Request $request)
	{
		Cart::add(
			$request->input('id'), $request->input('name'), (int)$request->input('qty'), (int)$request->input('price'),
			[
				'pic' => $request->input('pic')
			]
		);

		return response()->json([
			'cart_content_count' => Cart::content()->count(),
		]);
	}
}

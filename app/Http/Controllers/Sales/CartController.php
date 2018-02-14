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
			'cart_count' => Cart::content()->count(),
			'cart' => Cart::content(),
		]);
	}

    // 新增
	public function add(Request $request)
	{
		Cart::add($request->input('id'), $request->input('name'), (int)$request->input('qty'), (int)$request->input('price'));

		return Cart::content()->count();
	}
}

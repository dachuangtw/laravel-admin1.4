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
		return $this->display($request->user()->limited_time);
	}

    // 新增
	public function add(Request $request)
	{
		return $this->display($request->user()->limited_time);
	}
}

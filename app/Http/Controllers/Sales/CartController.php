<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
	public function index(Request $request)
	{
		if (strtotime($request->user()->collect_at) > time()) {
			// 還有領貨時間，顯示領貨功能
			return view('sales.cart', [
				'picking' => TRUE,
			]);

		} else {
            return redirect('product');
		}

	}
}

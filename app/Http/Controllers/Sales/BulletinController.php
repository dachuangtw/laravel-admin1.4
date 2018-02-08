<?php

namespace App\Http\Controllers\Sales;

use App\Models\Sales\SalesNote;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BulletinController extends Controller
{
	public function index(Request $request)
    {
		return view('sales.bulletin', [
			'notes' => salesNote::ofNotewid($request->user()->wid)
				->ofNoteTarget($request->user()->id)
				->with('hasOneWriter')
				->get(),
			'sales_note' => salesNote::class,
		]);
    }
}

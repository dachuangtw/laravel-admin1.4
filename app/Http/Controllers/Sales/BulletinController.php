<?php

namespace App\Http\Controllers\Sales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Sales\SalesNote;

class BulletinController extends Controller
{
	public function index(Request $request)
    {
		$bulletins = SalesNote::ofNotewid($request->user()->wid)
		->ofNoteTarget($request->user()->id)
		->with('hasOneWriter')
		->paginate(5);

		return view('sales.bulletin', [
				'bulletins' => $bulletins,
			]);
    }
}

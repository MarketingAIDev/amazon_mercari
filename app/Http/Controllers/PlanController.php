<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Plan;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PlanController extends Controller
{
	public function show()
	{
		$plans = Plan::all();
		return view('plan.index', ['plans' => $plans]);
	}
	
	public function select(Request $request)
	{
		$user = User::find(Auth::id());
		$user->plan_id = $request->id;
		$user->save();
		return redirect()->route('plan_page');  
	}

	public function page()
	{
		$plans = Plan::all();
		return view('plan.show', ['plans' => $plans]);
	}
}

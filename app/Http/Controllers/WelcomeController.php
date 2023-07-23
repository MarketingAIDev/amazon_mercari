<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Machine;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        if(!empty(Auth::user())){
        	// $id = Machine::where('user_id', Auth::user()->id)->first()->id;
         //    return redirect()->route("register_product", ['id' => $id]);
        	return redirect()->route("base_data");
        }else{            
            return redirect('login');
        } 
       
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;

class ActivateController extends Controller
{
    //
	public function index($token)
	{
			
		$users = User::where('token', $token)->first();
		
		if($token = $users->token && $users->is_activated == 0){

			User::where('id', $users->id)->update(['is_activated' => 1]);

		}


		 return redirect('/login');
	}

}

<?php

namespace Monitoring\Contact\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Users_activation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class RegisterController extends Controller
{
    //

    public function register(Request $request)
    {

    	$data = $request->all();
    	$validator = Validator::make($data, [
    		'name' => 'required|string',
    		'email' => 'required|email|unique:users,email',
    		'password' => 'required|confirmed|min:6',
    		'password_confirmation' => 'required|min:6',
    	]);

    	if($validator->fails()){
    		return response()->json([
                "status" => "error",
                "messages" => $validator->messages()
            ], 422);
    	}

        $data['password'] = bcrypt($data['password']);
        $data['token'] = uniqid();

        User::create($data);
    	
		Mail::to($data['email'])->send(new SendMail($data['token']));
    }



}

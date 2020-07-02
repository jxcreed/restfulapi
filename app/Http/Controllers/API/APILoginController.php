<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\UsersModel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\SendEmail;
use Socialite;
use Auth;
use Redirect;
use Session;
use Validator;
use App\User;
use Illuminate\Auth\Authenticatable;
use DB;

class APILoginController extends Controller
{
	public function login(Request $request) {

		$rules = [
    		'email' => 'required',
    		'password' => 'required',
    	];
    	
    	$validator = Validator::make($request->all(), $rules);
    	if($validator->fails()) {
    		return response()->json($validator->errors(), 400);
    	}

		try {
			$user_login = UsersModel::where('email', $request->email)
			->where('status', 1)
			->first();

			$validCredentials = Hash::check($request['password'], $user_login->password);
		}
		catch (\Exception $e) {
            $validCredentials = '';
        }

		if($validCredentials) {
			return response()->json(["message" => "Successfully login!"], 200);
		}
		else {
			return response()->json(["message" => "These credentials do not match our records."], 404);
		}
		
	}
}

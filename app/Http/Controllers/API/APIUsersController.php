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
use DB;

class APIUsersController extends Controller
{

    public function usersSave(Request $request) {

    	$rules = [
    		'email' => 'required',
    		'password' => 'required',
    	];
    	
    	$validator = Validator::make($request->all(), $rules);
    	if($validator->fails()) {
    		return response()->json($validator->errors(), 400);
    	}

    	$token = Str::random(100);

    	$users = UsersModel::create([
    		'token' => $token,
    		'firstname' => $request->firstname,
    		'lastname' => $request->lastname,
    		'gender' => $request->gender,
    		'email' => $request->email,
    		'password' => Hash::make($request->password),
    		'status' => 0,
    	]);

    	// Send Email Notification Verification
    	$user = array(
    		'token' => $token,
    		'firstname' => $request->firstname,
    		'lastname' => $request->lastname,
    		'gender' => $request->gender,
    		'email' => $request->email,
    		'password' => Hash::make($request->password),
    		'status' => 0,
    		'verificationlink' => 'http://'.$_SERVER['HTTP_HOST'].'/api/email-verification/'.$token,
    	);

    	$temp = "emails.registration_email_api";
        $subject = "Welcome to My Website! One Last Step";
        $email_user = $request->email;

        $mail = Mail::to($email_user)->send(new SendEmail($temp,$subject,$user));
        // End of Send Email Notification Verification

        $result_user = UsersModel::select('token','firstname','lastname','email','password', DB::raw("(
        	CASE 
        		WHEN status = 0 THEN 'Inactive'
				WHEN status = 1 THEN 'Active'
        	ELSE 'None' END) AS status"), 'id'
  		)->where('id', $users->id)->get();

        if(!$result_user->isEmpty()) { 
            return response()->json($result_user, 201);
        } else {
            return response()->json(["message" => "Failed to add record on database!"], 404);
        }
    }

    public function usersActivation() {
        
        $token = request()->segment(count(request()->segments()));

        try {
            $UpdateUser = User::where('token', $token)->first();
            $UpdateUser->status = 1;
            $UpdateUser->save();
        }
        catch (\Exception $e) {
            $UpdateUser = null;
        }

        $result_user_activate = UsersModel::select('token','firstname','lastname','email','password', DB::raw("(
            CASE 
                WHEN status = 0 THEN 'Inactive'
                WHEN status = 1 THEN 'Active'
            ELSE 'None' END) AS status"), 'id'
        )->where('token', $token)
        ->where('status', 1)
        ->first();

        if(is_null($UpdateUser)) { 
            return response()->json(["message" => "Failed to activate your account"], 404);
        } else {
            return response()->json($result_user_activate, 201);
        }
    }
    
}

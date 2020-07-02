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

    public function usersList() {

        try {
            $Users = UsersModel::get();
            foreach($Users as $key=>$user) {
                
                if(empty($Users[$key]['api_token'])) {
                    unset($Users[$key]['email']);
                    unset($Users[$key]['lastname']);
                }

                $Users[$key]['status'] = ($Users[$key]['status'] == 1) ? 'Active' : 'Inactive';
                unset($Users[$key]['password']);
                unset($Users[$key]['gender']);
                unset($Users[$key]['email_verified_at']);
                unset($Users[$key]['remember_token']);
                unset($Users[$key]['created_at']);
                unset($Users[$key]['updated_at']);
                $Users[$key]['bearer_token'] = $Users[$key]['api_token'];
                unset($Users[$key]['api_token']);
                
            }
        }
        catch (\Exception $e) {
            $Users = null;
        }

        if(!$Users->isEmpty()) { 
            return response()->json($Users, 201);
        } else {
            return response()->json(["message" => "No record found"], 404);
        }
    }

    public function changePassword(Request $request, $id) {

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_new_password' => 'required',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user_account = UsersModel::find($id);

        if(is_null($user_account)) {
            return response()->json(["message" => "Record not found!"], 404);
        }

        $validCredentials = Hash::check($request->current_password, $user_account->password);

        if($validCredentials) {
            if($request->new_password == $request->confirm_new_password) {
                $user_account->update([
                    'password' => Hash::make($request->new_password),
                ]);

                return response()->json($user_account, 200);
            }
            else {
                return response()->json(["message" => "New Password and Confirm New Password does not match!"], 404);
            }
        } else {
            return response()->json(["message" => "Current Password does not match to our record!"], 404);
        }

    }
    
}

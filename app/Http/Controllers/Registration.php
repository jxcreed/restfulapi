<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\SendEmail;
use Socialite;
use Auth;
use Redirect;
use Session;

class Registration extends Controller
{
    public function index() {

    	//
        
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function processregistration(Request $request) {

    	// dd($request);

        $token = Str::random(100);

        $user = new User;
        $user->token =  $token;
        $user->firstname =  $request->firstname;
        $user->lastname = $request->lastname;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->status = 0;
        $user->save();

        $request->verificationlink = 'http://'.$_SERVER['HTTP_HOST'].'/email-verification/'.$token;

        $temp = "emails.registration_email";
        $subject = "Welcome to My Website! One Last Step";
        $email_user = $request->email;

        $mail = Mail::to($email_user)->send(new SendEmail($temp,$subject,$request));

        return redirect()->back()->with('success', 'An email notification is sent to you, Please verify your account! Thank you!');

    }

    public function emailverification() {

        $token = request()->segment(count(request()->segments()));

        $UpdateUser = User::where('token', $token)->first();
        $UpdateUser->status = 1;
        $UpdateUser->save();

        return view('auth/verify');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    #create functions for login and signup user api
    public function login(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);
        $credentials = $request->only('email','password');
        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status'=>true,
                'message'=>'User logged in successfully',
                'data'=>[
                    'user'=>$user,
                    'token'=>$token
                ]
            ]);
        }
        return response()->json([
            'status'=>false,
            'message'=>'Invalid login details'
        ]);
    }
    #also provide signup user api
    public function signup(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed'
        ]);
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        ]);
        //create welcome email on user signup
        Mail::to($user->email)->send(new WelcomeMail($user));

        //provide code for welcome mail
        public function build()
        {
            return $this->markdown('emails.welcome');
        }
        //provide code for email.welcome blade
        @component('mail::message')
        //next
        @component('mail::button', ['url' => ''])
        //provide full code for blade
        @endcomponent
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status'=>true,
            'message'=>'User created successfully',
            'data'=>[
                'user'=>$user,
                'token'=>$token
            ]
        ]);
    }
}

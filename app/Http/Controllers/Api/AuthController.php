<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone_no' => ['required', 'string', 'min:10'],
        ]);
    
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 404);
        }
        
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        $token = $user->createToken('hobbyApp')->accessToken;
        return response()->json([
            'data'=> $user,
            'token' => $token 
        ], 201);
    }

    public function login(Request $request){
        $data = $request->all();
        $attempt = Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        if(!$attempt){
            return response()->json(['error' => 'Unauthorised'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('hobbyApp')->accessToken;
        return response()->json([
            'data'=> $user,
            'token' => $token 
        ], 200);

    }
}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\http\redirectResponse;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request){

    if(!auth()->attempt($request->only('email','password'))) {
        return response()->json([
            'status' => false,
            'message' => 'gagal login',
        ], 401);
    }
    $user = user::where('email', $request->email)->firstOrfail();
    $token= $user->createtoken('auth_token')->plainTextToken;

    return response()->json([
        'status' => true,
        'data' => $user,
        'access_token' => $token,
        'message' =>'login success',
    ],200);
}
    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'logout success',
        ],200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request){
        $formData = $request->validated();
        $user = User::create($formData);
        $token = $user->createToken("api token of $user->name")->plainTextToken;
        return response()->json([
            'token'=>$token,
            'user'=>$user
        ],201);
    }

    public function login(StoreLoginUserRequest $request){
        $formData = $request->validated();
        if(!Auth::attempt($formData)){
            return response()->json(['error'=>"Provided email or password is not correct."],422);
        }else{
            $user = User::where('email',$request->email)->first();
            $token =$user->createToken("api token of $user->name")->plainTextToken;
            return response()->json([
                'token'=>$token,
                'user'=>$user
            ],200);
        }
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Logout Successfully.']);
    }
}

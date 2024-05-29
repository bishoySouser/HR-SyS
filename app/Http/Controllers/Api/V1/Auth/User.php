<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class User extends Controller
{
    public function login(Request $request)
    {
        $loginUserData = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|min:8',
        ]);

        // Check if validation fails
        if ($loginUserData->fails()) {
            return response()->json([
                'message' => 'Request failed',
                'data' => $loginUserData->messages(),
            ], 400);
        }

        $user = Employee::where('email', $request->email)->first();

        // return $user;

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'Invalid Credentials',
            ], 401);
        }

        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
          "message"=>"logged out"
        ]);
    }
}

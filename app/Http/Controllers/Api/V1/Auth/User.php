<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

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
            'isManager' => $user->isManager()
        ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
          "message"=>"logged out"
        ]);
    }

    public function changePassword(Request $request){
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', Password::defaults()],
            'new_password_confirmation' => ['required', 'same:new_password'],
        ], [
            'new_password_confirmation.same' => 'The new password confirmation does not match.',
        ]);

        $user = $request->user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }
}

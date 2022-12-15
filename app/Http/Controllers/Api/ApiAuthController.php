<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    //login
    public function login(Request $request)
    {
        // validate request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|password'
        ]);
        // check if user exist
        $user = User::where('email', $request->email)->first();
        // check if password correct
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'Bad Credentials'
            ], 401);
        }

        // generate token
        return $user->createToken('token')->plainTextToken;
        // return response

    }

     //reqister
     public function reqister(Request $request)
     {

     }

     //logout
     public function logout(Request $request)
     {

     }

}

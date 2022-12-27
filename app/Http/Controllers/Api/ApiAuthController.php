<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\LoginResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    //login
    public function login(LoginRequest $request)
    {

        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials)){
            $user = User::where('email', $request->email)->first();
            // token lama dihapus
            $user->tokens()->delete();
            // token baru di create
            $token = $user->createToken('token')->plainTextToken;
            return new LoginResource([
                'token' => $token,
                'user' => $user
            ]);
        }else {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 404);
        }
        // // check if user exist
        // $user = User::where('email', $request->email)->first();
        // // check if password correct
        // if(!$user || !Hash::check($request->password, $user->password)){
        //     return response()->json([
        //         'message' => 'Bad Credentials'
        //     ], 401);
        // }

        // // generate token
        // $token = $user->createToken('token')->plainTextToken;

        // // return response
        // return new LoginResource([
        //     'token' => $token,
        //     'user' => $user

        // ]);

    }

     //reqister
     public function register(RegisterRequest $request)
     {

        // save to table
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('token')->plainTextToken;

        // return token
        return new LoginResource([
            'token' => $token,
            'user' => $user
        ]);
     }

     //logout
     public function logout(Request $request)
     {
        // delete token by user id
        // $request->user()->currentAccessToken()->delete();
        // delete all tokens
        $request->user()->tokens()->delete();

        return response()->noContent();

     }

}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only('logout');
    }

    public function register(Request $request)
    {
        // validate request
        $request->validate([
            'first_name' => 'bail | required | string | min:3 | max:20',
            'last_name' => 'bail | required | string | min:3 | max:20',
            'email' => 'bail | required | email | max:50 | unique:users',
            'password' => 'bail | required | min:4'
        ]);
        // hash password
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        // create user
        $user = User::create($request->all());
        // check if user created
        if (is_null($user)) {
            return response()->json(['message' => 'مشکلی در ثبت‌نام رخداد داده است']);
        }
        // return successful response
        return response()->json([
            'message' => 'ثبت‌نام با موفقیت انجام شد',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        // try to authenticate user
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json(['message' => 'اطلاعات وارد شده اشتباه است']);
        }

        $user = User::where('email', $request['email'])->first(); // find user with username
        $user->tokens()->delete();  // delete previous token
        $token = $user->createToken('auth')->plainTextToken;    // create token

        return response()->json([
            'message' => 'با موفقیت وارد شدید',
            'token' => $token,
            'user' => new UserResource($user)
        ]);
    }

    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'کاربر با موفقیت خارج شد']);
    }
}

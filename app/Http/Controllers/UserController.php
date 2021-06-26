<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['update', 'destroy']);
    }

    public function show($user)
    {
        // return user info
        return new UserResource(User::with('tasks')->findOrFail($user));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        // validate request
        $request->validate([
            'first_name' => 'bail | string | min:3 | max:20',
            'last_name' => 'bail | string | min:3 | max:20',
            'username' => 'bail | min:3 | max:20 | unique',
            'email' => 'bail | email | max:50',
        ]);
        // update user
        $user->update($request->all());
        // return successful response
        return response()->json([
            'message' => 'کاربر با موفقیت ویرایش شد',
        ]);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        // delete user's auth token
        $user->tokens()->delete();
        // delete user
        $user->delete();
        // return successful response
        return response()->json(['message' => 'کاربر با موفقیت حذف شد']);
    }
}

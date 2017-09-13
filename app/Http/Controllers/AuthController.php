<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['changePassword']]);
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:password',
        ]);

        return User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'api_token' => $this->generateRandomString(),
        ]);
    }

    public function login(Request $request)
    {
        $user = User::where([
            'email' => $request->get('email')
        ])->first();

        $password = $user !== null ? $user->password : '';

        if ($user === null || !Hash::check($request->get('password'), $password)) {
            return response()->json([
                'message' => 'Your Email or Password is incorrect',
            ], 422);
        }

        return $user;
    }

    public function changePassword(Request $request)
    {
        $user = \Auth::user();

        \Validator::extend('correct_password', function($attribute, $value) use ($user) {
            return Hash::check($value, $user->password);
        });

        $validator = \Validator::make($request->all(), [
            'old_password' => 'required|correct_password',
            'new_password' => 'required|min:6',
            'confirm_new_password' => 'required|min:6|same:new_password',
        ], [
            'old_password.correct_password' => 'Your old password is wrong',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->password = Hash::make($request->get('new_password'));
        $user->save();

        return response()->json([
            'message' => 'Your password is successfully changed'
        ]);
    }

    protected function generateRandomString($length = 60) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}

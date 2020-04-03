<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('admin');

            return response()->json([
                'token' => $token->plainTextToken
            ], 200);
        }

        return response()->json([
            'error' => 'Invalid Credentials'
        ], 403);
    }

    /**
     * logout user
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json('', 200);
    }
}

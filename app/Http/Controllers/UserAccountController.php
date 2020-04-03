<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserAccountController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $account)
    {
        abort_if($request->user()->id != $account->id, 401);

        $request->validate([
            'password' => 'required|confirmed|min:8',
            'password_previous' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($account->id)
            ],
        ]);

        $passwords_matched = Hash::check($request->password_previous, $request->user()->password);
        if (!$passwords_matched) {
            return response()->json([
                'message' => 'Your previous password does not match the database password'
            ], 403);
        }

        $account->update([
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['message' => 'account_updated'], 200);
    }
}

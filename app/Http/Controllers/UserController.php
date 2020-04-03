<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'id' => auth()->id(),
            'email' => auth()->user()->email
        ]);
    }
}

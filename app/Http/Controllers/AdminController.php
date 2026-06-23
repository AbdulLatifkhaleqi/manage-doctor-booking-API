<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function login()
    {
        return response()->json([
            'success' => true,
            'message' => 'Admin login successful',
            'token' => Str::random(60)
        ], 200);
    }
}
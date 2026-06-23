<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if (
            $email !== env('ADMIN_EMAIL') ||
            $password !== env('ADMIN_PASSWORD')
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        return $next($request);
    }
}
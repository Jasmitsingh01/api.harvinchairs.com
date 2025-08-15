<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $token = $user->tokens()
        ->where('name', 'auth_token')
        ->latest()
        ->first();
        if ($token && now() > $token->expires_at) {
            // Token has expired
            return response()->json(['message' => 'Token has expired, Login Again.'], 401);
        }

        return $next($request);
    }
}

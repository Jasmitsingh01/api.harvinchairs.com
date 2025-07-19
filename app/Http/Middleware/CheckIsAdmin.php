<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CheckIsAdmin
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

        if ($request->user() && $request->user()->is_admin == true) {
            return $next($request);
        }
        else{

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        throw ValidationException::withMessages([
            'email' => ['User not found.'],
        ])->redirectTo('login');
        }
        // return redirect('admin');
    }
}

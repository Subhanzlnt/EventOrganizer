<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (request()->header('AppToken') != app_token() && $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'status' => 401,
                'message' => 'Token Denied !',
                /*'response'=>[
                    'total'=>0,
                    'data'=>[]
                ]*/
            ]);
        } elseif (Auth::guard($guard)->guest()) {
            if ($request->is('api/*') || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'status' => 401,
                    'message' => 'Unauthenticated user',
                    /*'response'=>[
                        'total'=>0,
                        'data'=>[]
                    ]*/
                ]);
            } else {
                return redirect()->guest('login');
            }
        }
        return $next($request);
    }
}

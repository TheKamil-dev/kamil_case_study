<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OptionalAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // if ($request->bearerToken()) {
        //     Auth::setUser(
        //         Auth::guard('sanctum')->user()
        //     );
        // }
        if ($request->bearerToken()) {
            try {
                    Auth::setUser(
                        Auth::guard('sanctum')->user()
                    );
            } catch (\Throwable $e) {

                $response = [
                    'success' => false,
                    'message' => 'Token is Invalid!',
                ];

                return response()->json($response, 404);

            }
        }
        return $next($request);
    }
}

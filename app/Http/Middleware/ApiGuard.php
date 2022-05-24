<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\TokenManager;
class ApiGuard
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
        $tokenManager = new TokenManager();
        if ($tokenManager->tokenIsValid($request->api_token) === false) {
            return response()->json(['description' => 'access denied','code'=> '403'], 403);
        }
        return $next($request);
    }
}

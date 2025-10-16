<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $user = Auth::user();
        $endpoint = $request->path();
        $method = $request->method();
        $timestamp = now()->toDateTimeString();

        Log::channel('daily')->info('User Activity', [
            'user_id' => $user?->id,
            'email'   => $user?->email,
            'method'  => $method,
            'endpoint'=> $endpoint,
            'timestamp' => $timestamp,
            'ip'      => $request->ip()
        ]);

        return $response;
    }
}

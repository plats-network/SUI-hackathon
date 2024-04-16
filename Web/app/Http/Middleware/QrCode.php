<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class QrCode
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
        if (!isset($_SERVER['HTTP_X_CODE_30SHINE'])) {
            abort(response()->json([
                'success' => false,
                'status' => -1,
                'message' => 'Unauthenticated',], 401));
        }

        if (isset($_SERVER['HTTP_X_CODE_30SHINE']) 
            && $_SERVER['HTTP_X_CODE_30SHINE'] != config('app.30shine_hash')) {
            abort(response()->json([
                'success' => false,
                'status' => -1,
                'message' => 'Unauthenticated',], 401));
        }

        return $next($request);
    }
}

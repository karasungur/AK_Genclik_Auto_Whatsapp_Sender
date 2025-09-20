<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CsrfTokenRotate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! in_array($request->method(), ['GET', 'HEAD', 'OPTIONS', 'TRACE'], true)) {
            if ($request->session()->isStarted()) {
                $request->session()->regenerateToken();
            }
        }

        return $response;
    }
}

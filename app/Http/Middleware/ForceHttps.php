<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Redirect non-secure requests to HTTPS when running in production.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.env') === 'production' && !$request->secure()) {
            return redirect()->secure(
                $request->getRequestUri(),
                301
            );
        }

        return $next($request);
    }
}


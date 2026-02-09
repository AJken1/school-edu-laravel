<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Apply a baseline set of security headers without breaking CDN assets.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Generate a unique nonce for this request
        $nonce = base64_encode(random_bytes(16));
        
        // Store the nonce in the request so views can access it
        $request->attributes->set('csp_nonce', $nonce);
        
        /** @var Response $response */
        $response = $next($request);

        // Set basic security headers
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', "camera=(), microphone=(), geolocation=()");
        
        // Set strict transport security header in production
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Build Content Security Policy with nonce
        $csp = implode(' ', [
            "default-src 'self'",
            "script-src 'self' 'nonce-{$nonce}' https://cdn.tailwindcss.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com",
            "style-src 'self' 'nonce-{$nonce}' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com https://cdn.tailwindcss.com",
            "img-src 'self' data: https:",
            "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net",
            "connect-src 'self'",
            "frame-ancestors 'self'",
            "form-action 'self'",
            "base-uri 'self'",
            "object-src 'none'",
        ]);

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}

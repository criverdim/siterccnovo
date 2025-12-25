<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (isset($response->headers)) {
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN', false);
            $response->headers->set('X-Content-Type-Options', 'nosniff', false);
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin', false);
            $response->headers->set('X-XSS-Protection', '1; mode=block', false);

            $isSecure = false;
            try {
                $isSecure = $request->isSecure();
            } catch (\Throwable $e) {
                $isSecure = false;
            }

            $host = null;
            try {
                $host = $request->getHost();
            } catch (\Throwable $e) {
                $host = null;
            }

            if ($isSecure && $host && ! in_array($host, ['127.0.0.1', 'localhost'], true)) {
                if ($response->headers->get('Strict-Transport-Security') === null) {
                    $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload', false);
                }
            }
        }

        return $response;
    }
}


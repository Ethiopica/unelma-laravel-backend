<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     * Forces HTTPS redirect in production environments.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if we're behind a proxy that terminates SSL
        $isSecure = $request->isSecure() || 
                    $request->header('X-Forwarded-Proto') === 'https' ||
                    $request->header('X-Forwarded-Ssl') === 'on';

        // If not secure and in production, redirect to HTTPS
        if (!$isSecure && app()->environment('production')) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        // Set the request as secure for URL generation
        if ($request->header('X-Forwarded-Proto') === 'https') {
            $request->server->set('HTTPS', 'on');
        }

        return $next($request);
    }
}

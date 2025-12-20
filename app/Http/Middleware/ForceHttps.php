<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     * Forces HTTPS for URL generation in production environments.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if we're behind a proxy that terminates SSL
        $isSecure = $request->isSecure() || 
                    $request->header('X-Forwarded-Proto') === 'https' ||
                    $request->header('X-Forwarded-Ssl') === 'on';

        // Set the request as secure for URL generation when behind proxy
        if ($request->header('X-Forwarded-Proto') === 'https') {
            $request->server->set('HTTPS', 'on');
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Only redirect GET requests to HTTPS (don't redirect POST/PUT/DELETE)
        if (!$isSecure && app()->environment('production') && $request->isMethod('GET')) {
            return redirect()->secure($request->getRequestUri(), 302);
        }

        return $next($request);
    }
}

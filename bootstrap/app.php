<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
        ]);

        // Exclude Stripe webhook from CSRF protection
        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
            'stripe/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Format API validation errors as JSON - MUST be first to catch validation errors
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $errors = $e->errors();
                $firstError = collect($errors)->flatten()->first();
                
                return response()->json([
                    'success' => false,
                    'message' => $firstError ?? 'The given data was invalid.',
                    'errors' => $errors,
                ], 422);
            }
        });

        // Format ALL other API exceptions as JSON - Catch everything before views can render
        $exceptions->render(function (\Throwable $e, $request) {
            // Check if this is an API request
            if ($request->is('api/*') || $request->expectsJson() || $request->wantsJson()) {
                // Clean up error message - remove view-related details for cleaner API responses
                $errorMessage = $e->getMessage();
                if (str_contains($errorMessage, 'View:') || str_contains($errorMessage, 'Undefined variable')) {
                    // Extract a cleaner error message
                    if (str_contains($errorMessage, 'Undefined variable $errors')) {
                        $errorMessage = 'A validation error occurred, but the error details could not be displayed.';
                    } else {
                        $errorMessage = 'An error occurred while processing your request.';
                    }
                }
                
                \Log::error('API Error: '.$e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'request_path' => $request->path(),
                    'request_method' => $request->method(),
                    'full_message' => $e->getMessage(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => config('app.debug') ? $e->getMessage() : $errorMessage,
                    'error' => config('app.debug') ? [
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'type' => get_class($e),
                    ] : null,
                ], 500);
            }
        });
    })->create();

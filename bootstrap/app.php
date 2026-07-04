<?php

declare(strict_types=1);

use App\Exceptions\InvalidTenantCredentialsException;
use App\Exceptions\TenantNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Route middleware aliases.
        //
        // "tenant.session"  - web guard: restores the active tenant DB
        //                     connection from the authenticated session
        //                     on every request to a protected web route.
        // "tenant.header"   - api guard: resolves the active tenant DB
        //                     connection from the X-Client-Code header
        //                     on every stateless API request.
        //
        // Both MUST run before "auth" / "auth:sanctum" so the correct
        // tenant database is already active before the guard tries to
        // load the user (or, for Sanctum, the personal access token).
        $middleware->alias([
            'tenant.session' => \App\Http\Middleware\ResolveTenantFromSession::class,
            'tenant.header' => \App\Http\Middleware\ResolveTenantFromHeader::class,
        ]);

        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // Force our tenant-resolution middleware to run BEFORE Laravel's
        // own auth middleware, regardless of declaration order in routes
        // files. Laravel silently reorders custom middleware relative to
        // framework middleware unless it appears in this priority list -
        // without this, `auth`/`auth:sanctum` can run before the tenant
        // DB connection is established, causing "Database hosts array is
        // empty" errors on the very first authenticated query.
        $middleware->priority([
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\ResolveTenantFromSession::class,
            \App\Http\Middleware\ResolveTenantFromHeader::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Auth\Middleware\Authenticate::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->throttleApi();

        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo('/dashboard');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Never leak internal exception messages / stack traces to the
        // browser. Render clean, branded error pages for the common
        // HTTP error classes instead (404 / 403 / 500), and a generic
        // JSON envelope for API clients.
        $exceptions->render(function (TenantNotFoundException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
            }

            return response()->view('errors.404', ['message' => $e->getMessage()], 404);
        });

        $exceptions->render(function (InvalidTenantCredentialsException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['status' => false, 'message' => $e->getMessage()], 401);
            }

            return back()->withErrors(['email' => $e->getMessage()])->onlyInput('email');
        });

        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if (! $request->expectsJson() && ! $request->is('api/*')) {
                return response()->view('errors.404', [], 404);
            }
        });

        $exceptions->render(function (HttpException $e, $request) {
            if ($e->getStatusCode() === Response::HTTP_FORBIDDEN
                && ! $request->expectsJson() && ! $request->is('api/*')) {
                return response()->view('errors.403', [], 403);
            }
        });

        $exceptions->render(function (\Throwable $e, $request) {
            if (! app()->hasDebugModeEnabled()
                && ! $request->expectsJson() && ! $request->is('api/*')
                && ! ($e instanceof HttpException)) {
                return response()->view('errors.500', [], 500);
            }
        });
    })->create();
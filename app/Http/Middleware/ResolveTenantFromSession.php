<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exceptions\TenantNotFoundException;
use App\Services\AuthenticationService;
use App\Services\TenantResolverService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * On every request to a protected web route, the "tenant" connection
 * only lives for the duration of the previous request - PHP tears
 * down and rebuilds the whole container on each request. This
 * middleware re-establishes it from the client_code stashed in the
 * session at login time, BEFORE the "auth" middleware runs, so that
 * when Auth::user() resolves the tenant_users provider it queries the
 * correct physical database.
 */
final readonly class ResolveTenantFromSession
{
    public function __construct(
        private TenantResolverService $tenantResolver,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $clientCode = $request->session()->get(AuthenticationService::sessionTenantKey());

        if ($clientCode !== null) {
            try {
                $this->tenantResolver->connectByClientCode($clientCode);
            } catch (TenantNotFoundException) {
                // The client was deactivated/removed since login - force
                // the user back through the login flow rather than
                // continuing with a half-authenticated session.
                $request->session()->invalidate();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Your session is no longer valid. Please sign in again.']);
            }
        }

        return $next($request);
    }
}

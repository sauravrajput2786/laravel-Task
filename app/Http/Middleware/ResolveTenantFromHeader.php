<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exceptions\TenantNotFoundException;
use App\Services\TenantResolverService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Stateless equivalent of ResolveTenantFromSession for the JSON API.
 * A bearer token alone does not tell us which of the N tenant
 * databases to look it up against, so authenticated API clients must
 * also send the X-Client-Code header they received at login. This
 * middleware must run before `auth:sanctum` so the correct tenant
 * connection is active before Sanctum tries to load the token.
 */
final readonly class ResolveTenantFromHeader
{
    public function __construct(
        private TenantResolverService $tenantResolver,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $clientCode = $request->header('X-Client-Code');

        if (blank($clientCode)) {
            return response()->json([
                'status' => false,
                'message' => 'The X-Client-Code header is required.',
            ], 400);
        }

        try {
            $this->tenantResolver->connectByClientCode($clientCode);
        } catch (TenantNotFoundException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        }

        return $next($request);
    }
}

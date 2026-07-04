<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidTenantCredentialsException;
use App\Exceptions\TenantNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Tenant\TenantUser;
use App\Services\AuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AuthController extends Controller
{
    public function __construct(
        private readonly AuthenticationService $authenticationService,
    ) {
    }

    /**
     * POST /api/login
     *
     * Response shape matches the spec exactly:
     *   { "status": true, "token": "..." }
     * on success, with additional context fields for convenience.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authenticationService->loginForApi(
                $request->string('email')->toString(),
                $request->string('password')->toString(),
            );
        } catch (TenantNotFoundException|InvalidTenantCredentialsException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 401);
        }

        return response()->json([
            'status' => true,
            'token' => $result['token'],
            'token_type' => 'Bearer',
            'client_code' => $result['client']->client_code,
            'user' => [
                'id' => $result['user']->id,
                'name' => $result['user']->name,
                'email' => $result['user']->email,
            ],
        ]);
    }

    /**
     * GET /api/user
     * Protected by: tenant.header + auth:sanctum
     */
    public function user(Request $request): JsonResponse
    {
        /** @var TenantUser $user */
        $user = $request->user();

        return response()->json([
            'status' => true,
            'client_code' => current_tenant()?->client_code,
            'user' => $user->only(['id', 'name', 'email']),
        ]);
    }

    /**
     * POST /api/logout
     * Protected by: tenant.header + auth:sanctum
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authenticationService->logout($request);

        return response()->json(['status' => true, 'message' => 'Logged out successfully.']);
    }
}

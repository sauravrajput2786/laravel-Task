<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Exceptions\InvalidTenantCredentialsException;
use App\Exceptions\TenantNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthenticationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class LoginController extends Controller
{
    public function __construct(
        private readonly AuthenticationService $authenticationService,
    ) {
    }

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $this->authenticationService->loginForWeb(
                $request,
                $request->string('email')->toString(),
                $request->string('password')->toString(),
            );
        } catch (TenantNotFoundException|InvalidTenantCredentialsException $e) {
            // Both exception types are surfaced identically to avoid
            // revealing whether the email exists at all.
            return back()
                ->withErrors(['email' => $e->getMessage()])
                ->onlyInput('email');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $this->authenticationService->logout($request);

        return redirect()->route('login');
    }
}

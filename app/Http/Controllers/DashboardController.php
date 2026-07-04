<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant\TenantUser;
use App\Services\AuthenticationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        /** @var TenantUser $user */
        $user = $request->user();

        $this->authorize('view', $user);

        return view('dashboard', [
            'user' => $user,
            'client' => current_tenant(),
            'apiToken' => session(AuthenticationService::sessionTokenFlashKey()),
        ]);
    }
}

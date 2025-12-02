<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminAccess
{
    public function handle(Request $request, Closure $next, string $scope = 'panel')
    {
        if (env('PLAYWRIGHT') && app()->environment('testing')) {
            if ($request->is('testing/login-admin')) {
                return $next($request);
            }
        }
        $user = $request->user();
        if (! $user) {
            if ($request->is('admin/login') || $request->is('admin/login*')) {
                return $next($request);
            }
            return redirect('/admin/login');
        }

        $isMaster = (bool) ($user->is_master_admin ?? false);
        $canPanel = (bool) ($user->can_access_admin ?? false) || $isMaster || ($user->role === 'admin');

        if ($scope === 'settings') {
            if (! $isMaster) {
                Log::warning('admin.access.denied', ['user_id' => $user->id, 'scope' => $scope, 'path' => $request->path()]);
                abort(403, 'Acesso restrito ao administrador master');
            }

            return $next($request);
        }

        if (! $canPanel) {
            Log::notice('admin.access.blocked', ['user_id' => $user->id, 'scope' => $scope, 'path' => $request->path()]);

            abort(403, 'Acesso ao painel admin não autorizado');
        }

        return $next($request);
    }
}

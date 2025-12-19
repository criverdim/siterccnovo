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
            if ($request->boolean('fresh') || $request->boolean('reset')) {
                auth()->logout();
            }

            if ($request->is('admin/assets') || $request->is('admin/assets*')) {
                return $next($request);
            }

            if ($request->is('admin/login') || $request->is('admin/login*')) {
                return $next($request);
            }

            if ($request->is('admin')) {
                Log::info('admin.redirect.to_admin_login', ['path' => $request->path()]);

                return redirect('/admin/login');
            }

            if ($request->is('admin*')) {
                Log::info('admin.redirect.to_admin_login', ['path' => $request->path()]);

                return redirect('/admin/login');
            }

            Log::info('admin.redirect.generic_login', ['path' => $request->path()]);

            return redirect('/login');
        }

        $isMaster = (bool) ($user->is_master_admin ?? false);
        $canPanel = (bool) ($user->can_access_admin ?? false) || $isMaster || ($user->role === 'admin');

        if ($request->is('admin/login') || $request->is('admin/login*')) {
            if ($request->boolean('fresh') || $request->boolean('reset')) {
                auth()->logout();

                return $next($request);
            }
            if ($canPanel && ($user->status ?? 'active') === 'active') {
                Log::info('admin.login.redirect_authenticated', ['user_id' => $user->id]);

                return redirect('/admin');
            }
            Log::notice('admin.login.force_logout', ['user_id' => $user->id]);
            auth()->logout();

            return $next($request);
        }
        if ($request->is('admin')) {
            if ($request->boolean('fresh') || $request->boolean('reset')) {
                auth()->logout();
            }
            if ($canPanel && ($user->status ?? 'active') === 'active') {
                Log::info('admin.access.granted_root', ['user_id' => $user->id]);

                return $next($request);
            }
            Log::notice('admin.access.blocked_root', ['user_id' => $user->id]);
            auth()->logout();

            return redirect('/admin/login');
        }

        if ($scope === 'settings') {
            if (! $isMaster) {
                Log::warning('admin.access.denied', ['user_id' => $user->id, 'scope' => $scope, 'path' => $request->path()]);
                abort(403, 'Acesso restrito ao administrador master');
            }

            return $next($request);
        }

        if (! $canPanel) {
            Log::notice('admin.access.blocked', ['user_id' => $user->id, 'scope' => $scope, 'path' => $request->path()]);
            auth()->logout();

            return redirect('/admin?denied=1');
        }

        Log::info('admin.access.granted', ['user_id' => $user->id, 'scope' => $scope, 'path' => $request->path()]);

        return $next($request);
    }
}

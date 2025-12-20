<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PageAccess
{
    /** @var array<string> */
    protected array $restricted = [
        '/pastoreio',
    ];

    public function handle(Request $request, Closure $next)
    {
        $path = '/'.trim($request->path(), '/');

        if (in_array($path, $this->restricted, true)) {
            if (! auth()->check()) {
                return redirect('/login?redirect='.urlencode($request->fullUrl()));
            }
            $user = $request->user();
            if (! $user || ! method_exists($user, 'canAccessPage') || ! $user->canAccessPage($path)) {
                abort(403);
            }
        }

        return $next($request);
    }
}


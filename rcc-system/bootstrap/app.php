<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        \App\Providers\Filament\AdminPanelProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.access' => \App\Http\Middleware\AdminAccess::class,
            'page.access' => \App\Http\Middleware\PageAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            try {
                if (str_starts_with($request->path(), 'admin')) {
                    // Evita loops: se já estamos na página de login do painel (/admin),
                    // não redirecionar novamente para ela.
                    if ($request->is('admin')) {
                        return null;
                    }
                    \Illuminate\Support\Facades\Log::error('admin.error', [
                        'path' => $request->path(),
                        'message' => $e->getMessage(),
                    ]);
                    if ($e instanceof \Illuminate\Auth\AuthenticationException || $e instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException || $e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                        return redirect('/admin');
                    }
                    if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                        return response('Acesso negado', 403);
                    }
                    if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                        $status = $e->getStatusCode();
                        if ($status === 403) {
                            return response('Acesso negado', 403);
                        }
                        if ($status === 404) {
                            return response('Página não encontrada', 404);
                        }
                    }

                    return response('Erro interno', 500);
                }
            } catch (\Throwable $ignored) {
            }

            return null;
        });
    })->create();

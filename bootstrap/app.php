<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // La route de login s'appelle « connexion » (pas « login ») : rediriger
        // les visiteurs non authentifiés vers la bonne route plutôt que de
        // déclencher RouteNotFoundException (HTTP 500).
        $middleware->redirectGuestsTo(fn () => route('connexion'));
    })
    ->withExceptions(function (): void {
        //
    })->create();

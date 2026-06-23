<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Aucun service ou binding à enregistrer dans le conteneur pour le moment.
    }

    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.default');
    }
}

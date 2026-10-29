<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // The Next.js site expects bare arrays/objects (e.g. Array.isArray(data)
        // in src/lib/api.js), not the default { "data": ... } envelope.
        JsonResource::withoutWrapping();
    }
}

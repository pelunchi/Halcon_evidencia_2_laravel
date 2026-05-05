<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Custom Blade directive: @can_role(['Admin','Ventas']) ... @end_can_role
        Blade::directive('can_role', function ($expression) {
            return "<?php if(auth()->check() && auth()->user()->hasAnyRole($expression)): ?>";
        });

        Blade::directive('end_can_role', function () {
            return "<?php endif; ?>";
        });
    }
}

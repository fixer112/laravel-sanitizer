<?php

namespace Fixer112\Sanitizer;

use Fixer112\Sanitizer\Middleware\Sanitizer;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class SanitizerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/sanitizer.php' => config_path('sanitizer.php'),
        ], 'config');

        if (config('sanitizer.global', true)) {
            app(Kernel::class)->pushMiddleware(Sanitizer::class);
        }

    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/sanitizer.php', 'sanitizer');
    }
}

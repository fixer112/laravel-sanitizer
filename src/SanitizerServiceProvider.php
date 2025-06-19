<?php

namespace Fixer112\Sanitizer;

use Illuminate\Support\ServiceProvider;
use Fuvipi\InputSanitizer\Middleware\SanitizeInput;

class InputSanitizerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['router']->pushMiddlewareToGroup('web', SanitizeInput::class);
        $this->app['router']->pushMiddlewareToGroup('api', SanitizeInput::class);
    }

    public function register() {}
}

<?php

namespace App\Providers;

use App\Modules\OpenApi\Validator\OpenApiValidator;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class OpenApiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(OpenApiValidator::class);
    }
}
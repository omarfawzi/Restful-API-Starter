<?php

namespace App\Providers;

use App\Modules\User\Resolver\UserResolver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerResolvers();
    }

    private function registerResolvers(): void
    {
        $this->app->singleton(UserResolver::class);
    }
}
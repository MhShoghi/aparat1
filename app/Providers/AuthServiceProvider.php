<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        // Add passport routes
        Passport::routes();

        // Add expired time for tokens and refresh token
        Passport::tokensExpireIn(now()->addMinutes(config('auth.token_expiration.token')));
        Passport::refreshTokensExpireIn(now()->addMinutes(config('auth.token_expiration.refresh_token')));

    }
}

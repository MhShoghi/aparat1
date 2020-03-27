<?php

namespace App\Providers;

use App\Channel;
use App\Comment;
use App\Policies\CommentPolicy;
use App\Policies\UserPolicy;
use App\Policies\VideoPolicy;
use App\User;
use App\Video;
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
        Video::class => VideoPolicy::class,
        User::class => UserPolicy::class,
        Comment::class => CommentPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        // Add expired time for tokens and refresh token
        Passport::tokensExpireIn(now()->addMinutes(config('auth.token_expiration.token')));
        Passport::refreshTokensExpireIn(now()->addMinutes(config('auth.token_expiration.refresh_token')));

        $this->registerGates();

    }

    private function registerGates()
    {
        Gate::before(function ($user, $ability){

            if($user->isAdmin()){
                return true;
            }
        });
    }
}

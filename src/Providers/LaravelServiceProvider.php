<?php

namespace Iqbalatma\LaravelJwtAuth\Providers;


use App\Http\Middleware\Authenticate;
use Iqbalatma\LaravelJwtAuth\Middleware\AuthenticateJWT;

class LaravelServiceProvider extends \PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider
{
    public function register()
    {
        parent::register(); // TODO: Change the autogenerated stub
        $this->app->singleton(Authenticate::class, AuthenticateJWT::class);
    }
}

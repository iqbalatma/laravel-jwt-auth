# laravel-jwt-auth
Laravel jwt with access and refresh mechanism custom from PHP-Open-Source-Saver / jwt-auth

## Credits
This is package used [PHP-Open-Source-Saver/jwt-auth](https://github.com/PHP-Open-Source-Saver/jwt-auth) that forked from [tymondesigns/jwt-auth](https://github.com/tymondesigns/jwt-auth)
This packages just abstract PHP-Open-Source-Saver/jwt-auth and change refresh token behavior. 

## Why you need this package ?
PHP-Open-Source-Saver/jwt-auth package use only 1 token (access token) to get protected resource while I need 2 types of token (access and refresh).
I make access token with short ttl and refresh token with long ttl.
Why do i need 2 types of token ?
When access token been compromised, it just short time access so hacker has limited time and cannot re-invoke new token.
On PHP-Open-Source-Saver/jwt-auth to refresh token (re-invoke new token) is using access token
I change this behavior so we will have 2 types of token.
So when hacker get access token they cannot re-invoke new token because it's need refresh token.

The other case is when you set access token with short ttl, you cannot re-invoke new tokens because you need valid access token.
On this package when your access token is invalid you still can re-invoke new token with refresh token

## How to install this package ?

Install via composer
```
composer require iqbalatma/laravel-jwt-auth
```

Copy service profider into `config/app.php`
```
Iqbalatma\LaravelJwtAuth\Providers\LaravelServiceProvider::class,
```

Publish vendor using command below
```
php artisan vendor:publish --provider="Iqbalatma\LaravelJwtAuth\Providers\LaravelServiceProvider"
```

Generate jwt secret key 
```
php artisan jwt:secret
```
Package ready to use

## How to use this package ?
Here is how to use this package 
### Configure Auth Config Guard
Modify your `config/auth.php`
```php
<?php


    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],
    .....
    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    ],
```

### Update User Model
Modify your model contract and add some method that need to be implemented

```php
<?php

namespace App;

use Iqbalatma\LaravelJwtAuth\Contracts\Interfaces\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
```

### Use Middleware on Route
Here is example how to protect route with middleware

```php
<?php

  Route::get("users", [App\Http\Controllers\UserController::class, "index"])->middleware("api");

```

### How to invoke access token and refresh token
Here is how to invoke access token and refresh token
```php
<?php

use Iqbalatma\LaravelJwtAuth\Services\JWTService;

$jwtService = new JWTService();

$accessToken = $jwtService->invokeAccessToken($credentials);
$refreshToken = $jwtService->infokeRefreshToken();

```


<?php

namespace App\Http;

use App\Http\Middleware\CheckIsUserActivated;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\ModifyHeadersMiddleware::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            // \Silber\PageCache\Middleware\CacheResponse::class,
	       \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        'api' => [
            'throttle:360,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        'activated' => [
            CheckIsUserActivated::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'              => \App\Http\Middleware\Authenticate::class,
        'auth.basic'        => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings'          => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers'     => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can'               => \Illuminate\Auth\Middleware\Authorize::class,
        'guest'             => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm'  => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed'            => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle'          => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified'          => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'activated'         => CheckIsUserActivated::class,
        'role'              => \jeremykenedy\LaravelRoles\Middleware\VerifyRole::class,
        'permission'        => \jeremykenedy\LaravelRoles\Middleware\VerifyPermission::class,
        'level'             => \jeremykenedy\LaravelRoles\Middleware\VerifyLevel::class,
        'currentUser'       => \App\Http\Middleware\CheckCurrentUser::class,
        'mobile'            => \App\Http\Middleware\AuthMobile::class,
        'airnav'            => \App\Http\Middleware\AuthAirnav::class,
        'puta'              => \App\Http\Middleware\AuthPuta::class,
        'CORS'              => \App\Http\Middleware\CORS::class,
        'ajax-session-expired' => \App\Http\Middleware\AjaxSessionExpiredMiddleware::class,
    ];
}

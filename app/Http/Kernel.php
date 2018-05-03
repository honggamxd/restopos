<?php

namespace App\Http;

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
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    /*
        auth.level::
        1 - cashier only
        2 - restaurant admin and cashier only
        3 - restaurant admin only
        4 - restaurant admin and general admin only
        5 - general admin only
    */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.level.1' => \App\Http\Middleware\Cashier::class,
        'auth.level.2' => \App\Http\Middleware\RestaurantAdminAndCashier::class,
        'auth.level.3' => \App\Http\Middleware\RestaurantAdmin::class,
        'auth.level.4' => \App\Http\Middleware\RestaurantAndGeneralAdmin::class,
        'auth.level.5' => \App\Http\Middleware\Admin::class,
        'purchase_request' => \App\Http\Middleware\PurchaseRequest::class,
        'request_to_canvass' => \App\Http\Middleware\RequestToCanvass::class,
        'capital_expenditure_request' => \App\Http\Middleware\CapitalExpenditureRequest::class,
        'purchase_order' => \App\Http\Middleware\PurchaseOrder::class,
        'receiving_report' => \App\Http\Middleware\ReceivingReport::class,
        'stock_issuance' => \App\Http\Middleware\StockIssuance::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    ];
}

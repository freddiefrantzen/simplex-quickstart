<?php

use Simplex\ContainerKeys;
use Simplex\HttpMiddleware\DispatchController;
use Simplex\HttpMiddleware\LoadRoutes;
use Simplex\HttpMiddleware\MatchRoute;
use Simplex\HttpMiddleware\RegisterExceptionHandler;
use Simplex\HttpMiddleware\SetJsonResponseHeaders;
use Simplex\Quickstart\Shared\HttpMiddleware\HandleBadRequests;

return [

    ContainerKeys::EDITOR => DI\env('EDITOR', 'phpstorm'),

    ContainerKeys::MIDDLEWARE => [
        RegisterExceptionHandler::class,
        LoadRoutes::class,
        MatchRoute::class,
        HandleBadRequests::class,
        DispatchController::class,
        SetJsonResponseHeaders::class,
    ],

    'db.host' => DI\env('DATABASE_HOST', '127.0.0.1'),
    'db.port' => DI\env('DATABASE_PORT', '3306'),
    'db.name' => DI\env('DATABASE_NAME'),
    'db.user' => DI\env('DATABASE_USER'),
    'db.pass' => DI\env('DATABASE_PASS'),

];

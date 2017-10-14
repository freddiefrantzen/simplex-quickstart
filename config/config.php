<?php

use Simplex\HttpMiddleware\MatchRoute;
use Simplex\HttpMiddleware\DispatchController;
use Simplex\HttpMiddleware\LoadRoutes;
use Simplex\HttpMiddleware\SetJsonResponseHeaders;
use Simplex\HttpMiddleware\RegisterExceptionHandler;

return [

    'editor' => DI\env('EDITOR', 'phpstorm'),

    'cache_enabled' => DI\env('CACHE_ENABLED', false),
    'cache_dir' => __DIR__ . '/../cache/',

    'middleware' => [
        RegisterExceptionHandler::class,
        LoadRoutes::class,
        MatchRoute::class,
        DispatchController::class,
        SetJsonResponseHeaders::class,
    ],

    'db.host' => DI\env('DATABASE_HOST', '127.0.0.1'),
    'db.port' => DI\env('DATABASE_PORT', '3306'),
    'db.name' => DI\env('DATABASE_NAME'),
    'db.user' => DI\env('DATABASE_USER'),
    'db.pass' => DI\env('DATABASE_PASS'),

];

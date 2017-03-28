<?php

use Simplex\HttpMiddleware\MatchRoute;
use Simplex\HttpMiddleware\DispatchController;
use Simplex\HttpMiddleware\SetJsonResponseHeaders;

return [

    'editor' => DI\env('EDITOR', 'phpstorm'),

    'env' => DI\env('FRAMEWORK_ENV', 'dev'),
    'debug_mode' => DI\env('FRAMEWORK_DEBUG', false),

    'cache_dir' => __DIR__ . '/../cache/',

    'middleware' => [
        MatchRoute::class,
        DispatchController::class,
        SetJsonResponseHeaders::class,
    ],

    'db.host' => DI\env('DATABASE_HOST', 'localhost'),
    'db.port' => DI\env('DATABASE_PORT', '3306'),
    'db.name' => DI\env('DATABASE_NAME'),
    'db.user' => DI\env('DATABASE_USER'),
    'db.pass' => DI\env('DATABASE_PASS'),

];

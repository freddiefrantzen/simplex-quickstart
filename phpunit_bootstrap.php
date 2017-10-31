<?php

require __DIR__.'/vendor/autoload.php';

// Parameters set in .ev.testing will not be overloaded
if (is_readable(__DIR__  . '/.env.testing')) {
    $dotenv = new \Dotenv\Dotenv(__DIR__, '.env.testing');
    $dotenv->load();
}

<?php

$loader = require __DIR__.'/vendor/autoload.php';
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader([$loader, 'loadClass']);

if (is_readable(__DIR__  . '/.env.testing')) {
    $dotenv = new \Dotenv\Dotenv(__DIR__, '.env.testing');
    $dotenv->load();
}

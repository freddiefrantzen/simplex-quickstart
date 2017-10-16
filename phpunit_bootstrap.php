<?php

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/vendor/autoload.php';
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader([$loader, 'loadClass']);

// Parameters set in .ev.testing will not be overloaded
if (is_readable(__DIR__  . '/.env.testing')) {
    $dotenv = new \Dotenv\Dotenv(__DIR__, '.env.testing');
    $dotenv->load();
}

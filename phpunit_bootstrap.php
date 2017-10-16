<?php

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/vendor/autoload.php';
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader([$loader, 'loadClass']);

// So we can run the core tests in a quickstart test suite
$loader->add("org\\bovigo\\vfs\\", ['vendor/simplex/simplex/vendor/mikey179/vfsStream/src/main/php']);

// Parameters set in .ev.testing will not be overloaded
if (is_readable(__DIR__  . '/.env.testing')) {
    $dotenv = new \Dotenv\Dotenv(__DIR__, '.env.testing');
    $dotenv->load();
}

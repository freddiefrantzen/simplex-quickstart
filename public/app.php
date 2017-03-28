<?php

use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\Response;
use Simplex\Kernel;

$loader = require __DIR__.'/../vendor/autoload.php';
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader([$loader, 'loadClass']);

$request = ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$kernel = new Kernel(__DIR__ . '/../');
$kernel->boot();

$application = new \Simplex\HttpApplication($kernel);
$response = $application->handleRequest($request, new Response());

$emitter = new SapiEmitter();
$emitter->emit($response);

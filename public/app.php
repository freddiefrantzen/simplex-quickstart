<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Simplex\ConfigLoader;
use Simplex\HttpApplication;
use Simplex\Kernel;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\Response;

$loader = require __DIR__.'/../vendor/autoload.php';
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

$request = ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$config = (new ConfigLoader())->loadFromDirectory(__DIR__ . '/../config');

$kernel = new Kernel($config);
$kernel->boot();

$application = new HttpApplication($kernel);
$response = $application->handleRequest($request, new Response());

$emitter = new SapiEmitter();
$emitter->emit($response);

<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Simplex\ContainerBuilder;
use Simplex\HttpApplication;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\Response;

$loader = require __DIR__.'/../vendor/autoload.php';
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

$request = ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$container = (new ContainerBuilder(new \SplFileInfo(__DIR__ . '/../config')))->build();

$application = new HttpApplication($container);
$response = $application->handleRequest($request, new Response());

$emitter = new SapiEmitter();
$emitter->emit($response);

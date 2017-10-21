<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Simplex\Bootstrap;
use Simplex\HttpApplication;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

Bootstrap::init(__DIR__ . '/../config');

$application = new HttpApplication(Bootstrap::getContainer());

$request = ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$response = $application->handleRequest($request, new Response());

$emitter = new SapiEmitter();
$emitter->emit($response);

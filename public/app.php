<?php

use Simplex\Bootstrap;
use Simplex\HttpApplication;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

require __DIR__.'/../vendor/autoload.php';

$container = (new Bootstrap(__DIR__ . '/../'))->getContainer();

$application = new HttpApplication($container);

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

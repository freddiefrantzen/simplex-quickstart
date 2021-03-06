<?php

use Doctrine\ORM\EntityManager;
use JMS\Serializer\SerializerInterface;
use League\Tactician\CommandBus;
use Psr\Container\ContainerInterface;
use Simplex\ContainerKeys;
use Simplex\Quickstart\Shared\CommandBus\HandlerLocator;
use Simplex\Quickstart\Shared\Controller\AppController;
use Simplex\Quickstart\Shared\Factory\CommandBusFactory;
use Simplex\Quickstart\Shared\Factory\DoctrineOrmFactory;
use Simplex\Quickstart\Shared\Factory\SerializerFactory;
use Simplex\Quickstart\Shared\Factory\ValidatorFactory;
use Simplex\Quickstart\Shared\HttpMiddleware\HandleBadRequests;
use Simplex\Quickstart\Shared\Testing\FixtureLoader;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

return [

    ContainerKeys::CONTROLLER_DEPENDENCIES => DI\add(
        [
            AppController::class => [
                'urlGenerator' => DI\get(UrlGenerator::class),
                'serializer' => DI\get(SerializerInterface::class),
                'commandBus' => DI\get(CommandBus::class),
                'validator' => DI\get(ValidatorInterface::class),
            ]
        ]
    ),

    FixtureLoader::class => function(ContainerInterface $c) {
        return new FixtureLoader($c->get(EntityManager::class));
    },

    HandlerLocator::class => function(ContainerInterface $c) {
        return new HandlerLocator($c);
    },

    EntityManager::class => DI\factory([DoctrineOrmFactory::class, 'create'])
        ->parameter('host', DI\get('db.host'))
        ->parameter('port', DI\get('db.port'))
        ->parameter('name', DI\get('db.name'))
        ->parameter('user', DI\get('db.user'))
        ->parameter('pass', DI\get('db.pass'))
        ->parameter('cacheDir', DI\get(ContainerKeys::CACHE_DIRECTORY))
        ->parameter('enableCache', DI\get(ContainerKeys::ENABLE_CACHE)),

    SerializerInterface::class => DI\factory([SerializerFactory::class, 'create'])
        ->parameter('urlGenerator', DI\get(UrlGenerator::class))
        ->parameter('enableCache', DI\get(ContainerKeys::ENABLE_CACHE))
        ->parameter('cacheDir', DI\get(ContainerKeys::CACHE_DIRECTORY))
        ->parameter('debugMode', DI\get(ContainerKeys::DEBUG_MODE)),

    ValidatorInterface::class => DI\factory([ValidatorFactory::class, 'create'])
        ->parameter('enableCache', DI\get(ContainerKeys::ENABLE_CACHE))
        ->parameter('cacheDir', DI\get(ContainerKeys::CACHE_DIRECTORY)),

    CommandBus::class => DI\factory([CommandBusFactory::class, 'create'])
        ->parameter('locator', DI\get(HandlerLocator::class)),

    HandleBadRequests::class => function (ContainerInterface $c) {
        return new HandleBadRequests(
            $c->get(SerializerInterface::class)
        );
    },

];

<?php declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Simplex\Quickstart\Module\Demo\CommandHandler\RegisterHandler;
use Simplex\Quickstart\Module\Demo\Console\DemoCommand;
use Simplex\Quickstart\Module\Demo\Model\Person;
use Simplex\Quickstart\Module\Demo\Repository\PersonRepository;

return [

    FooCommand::class => function(ContainerInterface $c) {
        return new DemoCommand();
    },

    'console_commands' => DI\add([
        DI\get(FooCommand::class),
    ]),

    RegisterHandler::class => function(ContainerInterface $c) {
        return new RegisterHandler($c->get(EntityManager::class));
    },

    PersonRepository::class => function(ContainerInterface $c) {
        return $c->get(EntityManager::class)->getRepository(Person::class);
    },
];


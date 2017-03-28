<?php declare(strict_types=1);

use Interop\Container\ContainerInterface;
use Simplex\Quickstart\Module\Demo\Console\DemoCommand;
use Simplex\Quickstart\Module\Demo\CommandHandler\RegisterHandler;
use Simplex\Quickstart\Module\Demo\Repository\PersonRepository;
use Simplex\Quickstart\Module\Demo\Model\Person;

return [

    FooCommand::class => function(ContainerInterface $c) {
        return new DemoCommand();
    },

    'console_commands' => DI\add([
        DI\get(FooCommand::class),
    ]),

    RegisterHandler::class => function(ContainerInterface $c) {
        return new RegisterHandler($c->get('orm'));
    },

    PersonRepository::class => function(ContainerInterface $c) {
        return $c->get('orm')->getRepository(Person::class);
    },
];


<?php declare(strict_types=1);

use Interop\Container\ContainerInterface;
use JMS\Serializer\SerializerInterface;
use Simplex\Quickstart\Shared\Factory\SerializerFactory;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Simplex\Quickstart\Shared\Factory\DoctrineOrmFactory;
use Simplex\Quickstart\Shared\Console\LoadFixturesCommand;
use Simplex\Quickstart\Shared\Testing\FixtureLoader;
use Simplex\Quickstart\Shared\CommandBus\HandlerLocator;
use League\Tactician\CommandBus;
use Simplex\Quickstart\Shared\Factory\CommandBusFactory;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

return [

    'controller_dependencies' => DI\add([
        'urlGenerator' => DI\get(UrlGenerator::class),
        'serializer' => DI\get(SerializerInterface::class),
        'commandBus' => DI\get(CommandBus::class),
    ]),

    FixtureLoader::class => function(ContainerInterface $c) {
        return new FixtureLoader($c->get('orm'));
    },

    LoadFixturesCommand::class => function(ContainerInterface $c) {
        return new LoadFixturesCommand($c->get(FixtureLoader::class));
    },

    HandlerLocator::class => function(ContainerInterface $c) {
        return new HandlerLocator($c);
    },

    'console_commands' => DI\add([
        DI\get(LoadFixturesCommand::class),

        // DBAL Commands
        new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),
        new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),

        // ORM Commands
        new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
        new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
        new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
        new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
        new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
        new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
        new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
        new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
        new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
        new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
        new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
        new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
        new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),
        new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand(),
        new \Doctrine\ORM\Tools\Console\Command\InfoCommand(),
        new \Doctrine\ORM\Tools\Console\Command\MappingDescribeCommand(),
    ]),

    'orm' => DI\factory([DoctrineOrmFactory::class, 'create'])
        ->parameter('host', DI\get('db.host'))
        ->parameter('port', DI\get('db.port'))
        ->parameter('name', DI\get('db.name'))
        ->parameter('user', DI\get('db.user'))
        ->parameter('pass', DI\get('db.pass'))
        ->parameter('cacheDir', DI\get('cache_dir'))
        ->parameter('debugMode', DI\get('debug_mode')),

    'console_helper_set' => function (ContainerInterface $c) {
        $entityManager = $c->get('orm');
        $ormHelperSet = ConsoleRunner::createHelperSet($entityManager);
        return $ormHelperSet;
    },

    SerializerInterface::class => DI\factory([SerializerFactory::class, 'create'])
        ->parameter('urlGenerator', DI\get(UrlGenerator::class))
        ->parameter('cacheDir', DI\get('cache_dir'))
        ->parameter('debugMode', DI\get('debug_mode')),

    CommandBus::class => DI\factory([CommandBusFactory::class, 'create'])
        ->parameter('locator', DI\get(HandlerLocator::class)),

];

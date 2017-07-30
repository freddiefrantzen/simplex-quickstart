<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use JMS\Serializer\SerializerInterface;
use League\Tactician\CommandBus;
use Psr\Container\ContainerInterface;
use Simplex\Quickstart\Shared\CommandBus\HandlerLocator;
use Simplex\Quickstart\Shared\Console\CreateDatabaseCommand;
use Simplex\Quickstart\Shared\Console\DropDatabaseCommand;
use Simplex\Quickstart\Shared\Console\LoadFixturesCommand;
use Simplex\Quickstart\Shared\Factory\CommandBusFactory;
use Simplex\Quickstart\Shared\Factory\DoctrineOrmFactory;
use Simplex\Quickstart\Shared\Factory\SerializerFactory;
use Simplex\Quickstart\Shared\Testing\FixtureLoader;
use Symfony\Component\Routing\Generator\UrlGenerator;

return [

    'controller_dependencies' => DI\add([
        'urlGenerator' => DI\get(UrlGenerator::class),
        'serializer' => DI\get(SerializerInterface::class),
        'commandBus' => DI\get(CommandBus::class),
    ]),

    FixtureLoader::class => function(ContainerInterface $c) {
        return new FixtureLoader($c->get(EntityManager::class));
    },

    LoadFixturesCommand::class => function(ContainerInterface $c) {
        return new LoadFixturesCommand($c->get(FixtureLoader::class));
    },

    CreateDatabaseCommand::class => function(ContainerInterface $c) {
        return new CreateDatabaseCommand(
            $c->get('db.host'),
            $c->get('db.port'),
            $c->get('db.name'),
            $c->get('db.user'),
            $c->get('db.pass')
        );
    },

    DropDatabaseCommand::class => function(ContainerInterface $c) {
        return new DropDatabaseCommand(
            $c->get('db.host'),
            $c->get('db.port'),
            $c->get('db.name'),
            $c->get('db.user'),
            $c->get('db.pass')
        );
    },

    HandlerLocator::class => function(ContainerInterface $c) {
        return new HandlerLocator($c);
    },

    \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand::class => \DI\create(\Doctrine\DBAL\Tools\Console\Command\RunSqlCommand::class),
    \Doctrine\DBAL\Tools\Console\Command\ImportCommand::class => \DI\create(\Doctrine\DBAL\Tools\Console\Command\ImportCommand::class),
    \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand::class => \DI\create(\Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand::class),
    \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand::class => \DI\create(\Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand::class),
    \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand::class => \DI\create(\Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand::class),
    \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand::class => \DI\create(\Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand::class),
    \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand::class => \DI\create(\Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand::class),
    \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand::class => \DI\create(\Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand::class),
    \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand::class => \DI\create(\Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand::class),
    \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand::class => \DI\create(\Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand::class),
    \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand::class => \DI\create(\Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand::class),
    \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand::class => \DI\create(\Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand::class),
    \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand::class => \DI\create(\Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand::class),
    \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand::class => \DI\create(\Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand::class),
    \Doctrine\ORM\Tools\Console\Command\RunDqlCommand::class => \DI\create(\Doctrine\ORM\Tools\Console\Command\RunDqlCommand::class),
    \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand::class => \DI\create(\Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand::class),
    \Doctrine\ORM\Tools\Console\Command\InfoCommand::class => \DI\create(\Doctrine\ORM\Tools\Console\Command\InfoCommand::class),
    \Doctrine\ORM\Tools\Console\Command\MappingDescribeCommand::class => \DI\create(\Doctrine\ORM\Tools\Console\Command\MappingDescribeCommand::class),

    'console_commands' => DI\add([
        DI\get(LoadFixturesCommand::class),
        DI\get(CreateDatabaseCommand::class),
        DI\get(DropDatabaseCommand::class),
        // ORM Commands
        DI\get(\Doctrine\DBAL\Tools\Console\Command\RunSqlCommand::class),
        DI\get(\Doctrine\DBAL\Tools\Console\Command\ImportCommand::class),
        // DBAL Commands
        DI\get(\Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand::class),
        DI\get(\Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand::class),
        DI\get(\Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand::class),
        DI\get(\Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand::class),
        DI\get(\Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand::class),
        DI\get(\Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand::class),
        DI\get(\Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand::class),
        DI\get(\Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand::class),
        DI\get(\Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand::class),
        DI\get(\Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand::class),
        DI\get(\Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand::class),
        DI\get(\Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand::class),
        DI\get(\Doctrine\ORM\Tools\Console\Command\RunDqlCommand::class),
        DI\get(\Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand::class),
        DI\get(\Doctrine\ORM\Tools\Console\Command\InfoCommand::class),
        DI\get(\Doctrine\ORM\Tools\Console\Command\MappingDescribeCommand::class),
    ]),

    EntityManager::class => DI\factory([DoctrineOrmFactory::class, 'create'])
        ->parameter('host', DI\get('db.host'))
        ->parameter('port', DI\get('db.port'))
        ->parameter('name', DI\get('db.name'))
        ->parameter('user', DI\get('db.user'))
        ->parameter('pass', DI\get('db.pass'))
        ->parameter('cacheDir', DI\get('cache_dir'))
        ->parameter('debugMode', DI\get('debug_mode')),

    'console_helper_set' => function (ContainerInterface $c) {
        $ormHelperSet = ConsoleRunner::createHelperSet($c->get(EntityManager::class));
        return $ormHelperSet;
    },

    SerializerInterface::class => DI\factory([SerializerFactory::class, 'create'])
        ->parameter('urlGenerator', DI\get(UrlGenerator::class))
        ->parameter('cacheDir', DI\get('cache_dir'))
        ->parameter('debugMode', DI\get('debug_mode')),

    CommandBus::class => DI\factory([CommandBusFactory::class, 'create'])
        ->parameter('locator', DI\get(HandlerLocator::class)),

];

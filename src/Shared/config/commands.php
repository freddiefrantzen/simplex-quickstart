<?php declare(strict_types=1);

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Psr\Container\ContainerInterface;
use Simplex\ContainerKeys;
use Simplex\Quickstart\Shared\Console\CreateDatabaseCommand;
use Simplex\Quickstart\Shared\Console\DropDatabaseCommand;
use Simplex\Quickstart\Shared\Console\LoadFixturesCommand;
use Simplex\Quickstart\Shared\Testing\FixtureLoader;

return [

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

    // @codingStandardsIgnoreStart

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

    // @codingStandardsIgnoreEnd

    ContainerKeys::CONSOLE_COMMANDS => DI\add([
        DI\get(CreateDatabaseCommand::class),
        DI\get(DropDatabaseCommand::class),
        DI\get(LoadFixturesCommand::class),

        DI\get(\Doctrine\DBAL\Tools\Console\Command\RunSqlCommand::class),
        DI\get(\Doctrine\DBAL\Tools\Console\Command\ImportCommand::class),

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

    ContainerKeys::CONSOLE_HELPER_SET => function (ContainerInterface $c) {
        return ConsoleRunner::createHelperSet($c->get(EntityManager::class));
    },

];

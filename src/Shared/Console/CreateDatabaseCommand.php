<?php

namespace Simplex\Quickstart\Shared\Console;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDatabaseCommand extends Command
{
    /** @var Connection */
    private $connection;

    /** @var string */
    private $name;

    public function __construct(
        string $host,
        string $port,
        string $name,
        string $user,
        string $pass)
    {
        $this->connection = DriverManager::getConnection(
            [
                'driver' => 'pdo_mysql',
                'host' => $host,
                'port' => $port,
                'user' => $user,
                'password' => $pass,
            ]
        );

        $this->name = $name;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('orm:database:create')
            ->setDescription('Creates the configured database')
            ->addOption('if-not-exists', null, InputOption::VALUE_NONE, 'Don\'t trigger an error, when the database already exists')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('if-not-exists') && in_array($this->name, $this->connection->getSchemaManager()->listDatabases())) {
            return;
        }

        $this->connection->getSchemaManager()->createDatabase($this->name);
        $output->writeln(sprintf('<info>Created database <comment>%s</comment></info>', $this->name));
    }
}
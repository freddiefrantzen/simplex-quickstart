<?php declare(strict_types = 1);

namespace Simplex\Quickstart\Shared\Console;

use Simplex\Quickstart\Shared\Testing\FixtureLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadFixturesCommand extends Command
{
    const COMMAND_NAME = 'app:load-fixtures';

    /** @var FixtureLoader */
    private $loader;

    public function __construct(FixtureLoader $loader)
    {
        $this->loader = $loader;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Loads the database fixtures')
        ;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->loader->load();
    }
}

<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Console;

use Simplex\Quickstart\Shared\Testing\FixtureLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadFixturesCommand extends Command
{
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
            ->setName('app:load-fixtures')
            ->setDescription('Loads the database fixtures')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->loader->load();
    }
}

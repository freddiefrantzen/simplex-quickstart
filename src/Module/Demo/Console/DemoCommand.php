<?php declare(strict_types=1);

namespace Simplex\Quickstart\Module\Demo\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DemoCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:demo')
            ->setDescription('Example command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
    }
}

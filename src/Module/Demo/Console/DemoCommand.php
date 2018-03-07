<?php declare(strict_types = 1);

namespace Simplex\Quickstart\Module\Demo\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DemoCommand extends Command
{
    private const COMMAND_NAME = 'app:demo';

    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Example command')
        ;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
    }
}

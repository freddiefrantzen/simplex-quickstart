<?php declare(strict_types = 1);

namespace Simplex\Quickstart\Functional\Test;

use PHPUnit\Framework\TestCase;
use Simplex\Quickstart\Shared\Console\CreateDatabaseCommand;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

final class ConsoleRunnerTest extends TestCase
{
    const PATH_TO_CONSOLE_RUNNER = 'bin/console';

    public function test_console_runner()
    {
        $process = new Process(self::PATH_TO_CONSOLE_RUNNER);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        self::assertContains(CreateDatabaseCommand::COMMAND_NAME, $process->getOutput());
    }
}

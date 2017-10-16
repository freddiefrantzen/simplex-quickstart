<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Testing;

use Symfony\Component\Process\Process;

trait PhpWebServer
{
    /** @var string */
    private static $webserverDocroot = 'public';

    /** @var string */
    private static $webserverRouter = 'public/app.php';

    /** @var Process */
    private static $webserverProcess;

    /**
     * @beforeClass
     */
    public static function startWebserver(): void
    {
        /** @noinspection PhpUndefinedConstantInspection */
        $command = sprintf(
            'php -S %s:%d -t %s %s',
            PHP_WEBSERVER_HOST,
            (int) PHP_WEBSERVER_PORT,
            self::$webserverDocroot,
            self::$webserverRouter
        );

        self::$webserverProcess = new Process($command);

        self::$webserverProcess->start();

        self::verifyProcessRunning(self::$webserverProcess);
    }

    private static function verifyProcessRunning(Process $process): void
    {
        if (!$process->isRunning()) {
            throw new \RuntimeException(sprintf(
                'Failed to start "%s" in background: %s',
                $process->getCommandLine(),
                $process->getErrorOutput()
            ));
        }
    }

    /**
     * @afterClass
     */
    public static function stopWebserver(): void
    {
        self::$webserverProcess->stop();
    }
}

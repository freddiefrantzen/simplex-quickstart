<?php declare(strict_types=1);

namespace Simplex\Quickstart\Functional\Test;

use PHPUnit\Framework\TestCase;
use Simplex\Quickstart\Module\Demo\DataFixture\PersonLoader;
use Simplex\Quickstart\Shared\Testing\PhpWebServer;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class WebApplicationTest extends TestCase
{
    use PhpWebServer;

    private $intervalDuration = 60000; // 60 ms
    private $numberOfRetries = 34; // max 2.04 seconds

    public function testWebApplication()
    {
        $acceptHeader = '"Accept: application/json"';

        /** @noinspection PhpUndefinedConstantInspection */
        $host = PHP_WEBSERVER_HOST;
        /** @noinspection PhpUndefinedConstantInspection */
        $port = PHP_WEBSERVER_PORT;

        $process = new Process("curl -H $acceptHeader http://$host:$port/");

        $this->runProcessWithRetry($process);

        self::assertContains('"name":"'. PersonLoader::PERSON_1_NAME . '"', $process->getOutput());
    }

    private function runProcessWithRetry(Process $process): void
    {
        for ($i = 0; $i < $this->numberOfRetries; ++$i) {

            usleep($this->intervalDuration);

            $process->run();

            if ($process->isSuccessful()) {
                return;
            }
        }

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}

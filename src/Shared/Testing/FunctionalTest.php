<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Testing;

use Helmich\JsonAssert\JsonAssertions;
use Helmich\Psr7Assert\Psr7Assertions;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Simplex\Kernel;

class FunctionalTest extends TestCase
{
    use JsonAssertions;
    use Psr7Assertions;

    protected $reloadDb = true;

    /** @var Kernel */
    private static $kernel;

    protected function setUp()
    {
        if ($this->reloadDb) {
            $loader = $this->getKernel()->getContainer()->get(FixtureLoader::class);
            $loader->load();
        }

        parent::setUp();
    }

    protected function tearDown()
    {
        m::close();

        parent::tearDown();
    }

    public function setInContainer($name, $value): void
    {
        $this->getKernel()->getContainer()->set($name, $value);
    }

    public function getFromContainer(string $id)
    {
        return $this->getKernel()->getContainer()->get($id);
    }

    public function createHttpRequest(): HttpRequest
    {
        return new HttpRequest($this->getKernel());
    }

    public function getKernel(): Kernel
    {
        if (!isset(self::$kernel)) {
            self::$kernel = new Kernel(__DIR__ . '/../../../');
            self::$kernel->boot();
        }

        return self::$kernel;
    }

    public function getBody(ResponseInterface $response): array
    {
        return json_decode((string) $response->getBody(), true);
    }

    public function getRawBody(ResponseInterface $response): string
    {
        return (string) $response->getBody();
    }
}

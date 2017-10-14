<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Testing;

use DI\Container;
use Helmich\JsonAssert\JsonAssertions;
use Helmich\Psr7Assert\Psr7Assertions;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Simplex\Bootstrap;

class FunctionalTest extends TestCase
{
    use JsonAssertions;
    use Psr7Assertions;

    protected $reloadDb = true;

    /** @var Container */
    private static $container;

    protected function setUp()
    {
        if ($this->reloadDb) {
            $loader = $this->getContainer()->get(FixtureLoader::class);
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
        $this->getContainer()->set($name, $value);
    }

    public function getFromContainer(string $id)
    {
        return $this->getContainer()->get($id);
    }

    public function createHttpRequest(): HttpRequest
    {
        return new HttpRequest($this->getContainer());
    }

    public function getContainer(): Container
    {
        if (!isset(self::$container)) {
            Bootstrap::init(__DIR__ . '/../../../config');
            self::$container = Bootstrap::getContainer();
        }

        return self::$container;
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

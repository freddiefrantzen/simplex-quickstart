<?php declare(strict_types = 1);

namespace Simplex\Quickstart\Shared\Testing;

use DI\Container;
use Helmich\JsonAssert\JsonAssertions;
use Helmich\Psr7Assert\Psr7Assertions;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Simplex\Bootstrap;

abstract class FunctionalTest extends TestCase
{
    use HttpRequestCapabilities;
    use JsonAssertions;
    use Psr7Assertions;

    private const PATH_TO_PROJECT_ROOT = __DIR__ . '/../../../';

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

        $this->reloadDb = true;

        parent::tearDown();
    }

    public function getContainer(): Container
    {
        if (!isset(self::$container)) {
            self::$container = (new Bootstrap(self::PATH_TO_PROJECT_ROOT))->getContainer();
        }

        return self::$container;
    }

    public function getFromContainer(string $id)
    {
        return $this->getContainer()->get($id);
    }

    public function setInContainer($name, $value): void
    {
        $this->getContainer()->set($name, $value);
    }
}

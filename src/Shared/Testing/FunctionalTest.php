<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Testing;

use DI\Container;
use Helmich\JsonAssert\JsonAssertions;
use Helmich\Psr7Assert\Psr7Assertions;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Simplex\Bootstrap;

class FunctionalTest extends TestCase
{
    use HttpRequestCapabilities;
    use JsonAssertions;
    use Psr7Assertions;

    const CONFIG_DIRECTORY_PATH = __DIR__ . '/../../../config';

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
            Bootstrap::init(self::CONFIG_DIRECTORY_PATH);
            self::$container = Bootstrap::getContainer();
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

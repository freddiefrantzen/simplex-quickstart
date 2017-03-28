<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Testing;

use PHPUnit\Framework\TestCase;
use Helmich\Psr7Assert\Psr7Assertions;
use Mockery as m;

class UnitTest extends TestCase
{
    use Psr7Assertions;

    protected function tearDown()
    {
        m::close();

        parent::tearDown();
    }
}

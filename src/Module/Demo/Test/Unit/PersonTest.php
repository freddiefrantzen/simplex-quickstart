<?php declare(strict_types=1);

namespace Simplex\Quickstart\Module\Demo\Test\Unit;

use Simplex\Quickstart\Shared\Testing\UnitTest;
use Simplex\Quickstart\Module\Demo\Model\Person;

class PersonTest extends UnitTest
{
    public function test_it_can_be_registered()
    {
        $person = Person::register('John Jones', 'john@example.com', 'foobar');

        $this->assertEquals('John Jones', $person->getName());
        $this->assertEquals('john@example.com', $person->getEmail());
    }
}

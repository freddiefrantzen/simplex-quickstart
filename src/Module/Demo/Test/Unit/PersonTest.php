<?php declare(strict_types = 1);

namespace Simplex\Quickstart\Module\Demo\Test\Unit;

use Simplex\Quickstart\Module\Demo\Model\Person;
use Simplex\Quickstart\Shared\Testing\UnitTest;

class PersonTest extends UnitTest
{
    const NAME = 'John Jones';
    const EMAIL = 'john@example.com';
    const PASSWORD = 'foobar';

    public function test_it_can_be_registered()
    {
        $person = Person::register(self::NAME, self::EMAIL, self::PASSWORD);

        $this->assertEquals(self::NAME, $person->getName());
        $this->assertEquals(self::EMAIL, $person->getEmail());
    }
}

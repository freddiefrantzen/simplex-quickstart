<?php declare(strict_types=1);

namespace Simplex\Quickstart\Module\Demo\DataFixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Simplex\Quickstart\Module\Demo\Model\Person;
use Simplex\Quickstart\Shared\Testing\ReflectionPropertyCapabilities;

class PersonLoader extends AbstractFixture
{
    use ReflectionPropertyCapabilities;

    const PERSON_1_NAME = 'Joe Smith';
    const PERSON_1_EMAIL = 'joe@example.com';
    const PERSON_1_PASSWORD = 'foobar';
    const PERSON_1_ID = '1dd60ffa-6e48-47f2-a03a-f89620c17e03';

    public function load(ObjectManager $manager)
    {
        $person = Person::register(
            self::PERSON_1_NAME,
            self::PERSON_1_EMAIL,
            self::PERSON_1_PASSWORD
        );

        $this->setProperty($person, 'id', Uuid::fromString(self::PERSON_1_ID));

        $manager->persist($person);
        $manager->flush();
    }
}

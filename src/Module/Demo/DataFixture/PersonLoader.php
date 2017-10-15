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

    const STATIC_ID = '1dd60ffa-6e48-47f2-a03a-f89620c17e03';

    public function load(ObjectManager $manager)
    {
        $person = Person::register('Joe Smith', 'joe@example.com', 'foobar');

        $this->setProperty($person, 'id', Uuid::fromString(self::STATIC_ID));

        $manager->persist($person);
        $manager->flush();
    }
}

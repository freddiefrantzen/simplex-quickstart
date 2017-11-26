<?php declare(strict_types = 1);

namespace Simplex\Quickstart\Shared\Testing;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Simplex\Quickstart\Module\Demo\DataFixture\PersonLoader;

class FixtureLoader
{
    /** @var EntityManager */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function load(): void
    {
        $loader = new Loader();
        $loader->addFixture(new PersonLoader());

        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }
}

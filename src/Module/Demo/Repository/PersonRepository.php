<?php declare(strict_types=1);

namespace Simplex\Quickstart\Module\Demo\Repository;

use Doctrine\ORM\EntityRepository;
use Simplex\Exception\ResourceNotFoundException;
use Simplex\Quickstart\Module\Demo\Model\Person;

class PersonRepository extends EntityRepository
{
    public function ofId(string $id): Person
    {
        $person = $this->find($id);

        if (!$person instanceof Person) {
            throw new ResourceNotFoundException();
        }

        return $person;
    }

    public function ofUsername(string $username): Person
    {
        $person = $this->findOneBy(['email' => $username]);

        if (!$person instanceof Person) {
            throw new ResourceNotFoundException();
        }

        return $person;
    }

    public function save(Person $person)
    {
        $this->getEntityManager()->persist($person);
        $this->getEntityManager()->flush($person);
    }
}

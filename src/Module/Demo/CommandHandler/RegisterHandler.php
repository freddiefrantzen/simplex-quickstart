<?php declare(strict_types = 1);

namespace Simplex\Quickstart\Module\Demo\CommandHandler;

use Doctrine\ORM\EntityManager;
use Simplex\Quickstart\Module\Demo\Command\Register;
use Simplex\Quickstart\Module\Demo\Model\Person;

final class RegisterHandler
{
    /**
     * @var EntityManager
     */
    private $orm;

    public function __construct(EntityManager $orm)
    {
        $this->orm = $orm;
    }

    public function handle(Register $command): void
    {
        $person = Person::register($command->name, $command->email, $command->password);

        $this->orm->persist($person);
        $this->orm->flush();
    }
}

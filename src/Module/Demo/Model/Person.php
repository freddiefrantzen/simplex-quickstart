<?php declare(strict_types=1);

namespace Simplex\Quickstart\Module\Demo\Model;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass="\Simplex\Quickstart\Module\Demo\Repository\PersonRepository")
 *
 * @Hateoas\Relation(
 *     name = "self",
 *     href = @Hateoas\Route(
 *         "get_person",
 *         parameters = { "id" = "expr(object.getId())" },
 *         absolute=true
 *     )
 * )
 */
class Person
{
    /**
     * @var Uuid
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="NONE")
     *
     * @Serializer\Exclude()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $passwordHash;

    private function __construct() {}

    public static function register(string $name, string $email, string $password)
    {
        $person = new self();

        $person->id = Uuid::uuid4();
        $person->name = $name;
        $person->email = $email;
        $person->passwordHash = password_hash($password, \PASSWORD_DEFAULT);

        return $person;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getAppId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}

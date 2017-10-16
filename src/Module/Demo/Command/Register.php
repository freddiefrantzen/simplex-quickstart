<?php declare(strict_types=1);

namespace Simplex\Quickstart\Module\Demo\Command;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class Register
{
    /**
     * @var string
     *
     * @Serializer\Type("string")
     *
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @var string
     *
     * @Serializer\Type("string")
     *
     * @Assert\NotBlank()
     */
    public $password;
}

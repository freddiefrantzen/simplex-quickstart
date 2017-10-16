<?php declare(strict_types=1);

namespace Simplex\Quickstart\Module\Demo\Command;

use JMS\Serializer\Annotation as Serializer;

class Register
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $name;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $email;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $password;
}

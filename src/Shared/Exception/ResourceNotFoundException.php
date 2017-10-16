<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Exception;

class ResourceNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Resource not found', 404);
    }
}

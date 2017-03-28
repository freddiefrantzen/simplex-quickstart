<?php declare(strict_types=1);

namespace Simplex\Quickstart\Module\Demo\Exception;

class ResourceNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Resource not found', 404);
    }
}

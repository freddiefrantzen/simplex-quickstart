<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared;

use Simplex\Module;

class SharedModule implements Module
{
    public function getServiceConfigDirectory(): ?string
    {
        return __DIR__ . '/config';
    }
}

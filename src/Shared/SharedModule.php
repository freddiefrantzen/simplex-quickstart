<?php declare(strict_types = 1);

namespace Simplex\Quickstart\Shared;

use Simplex\Module;

final class SharedModule implements Module
{
    public function getServiceDefinitionsDirectory(): ?string
    {
        return __DIR__ . '/DependencyInjection';
    }
}

<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Factory;

use Hateoas\Hateoas;
use Hateoas\HateoasBuilder;
use Hateoas\UrlGenerator\SymfonyUrlGenerator;
use Symfony\Component\Routing\Generator\UrlGenerator;

class SerializerFactory
{
    public function create(UrlGenerator $urlGenerator, string $cacheDir, bool $debugMode): Hateoas
    {
        $hateoas = HateoasBuilder::create()
            ->setUrlGenerator(null, new SymfonyUrlGenerator($urlGenerator))
            ->setCacheDir($cacheDir . 'serializer/')
            ->setDebug($debugMode)
            ->build()
        ;

        return $hateoas;
    }
}


<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Factory;

use Hateoas\Hateoas;
use Hateoas\HateoasBuilder;
use Hateoas\UrlGenerator\SymfonyUrlGenerator;
use Symfony\Component\Routing\Generator\UrlGenerator;

class SerializerFactory
{
    const SERIALIZER_CACHE_DIR = DIRECTORY_SEPARATOR . 'serializer';

    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function create(
        UrlGenerator $urlGenerator,
        bool $enableCache,
        \SplFileInfo $cacheDir,
        bool $debugMode
    ): Hateoas {

        if ($enableCache) {
            return HateoasBuilder::create()
                ->setUrlGenerator(null, new SymfonyUrlGenerator($urlGenerator))
                ->setCacheDir($cacheDir->getPathname(). self::SERIALIZER_CACHE_DIR)
                ->setDebug($debugMode)
                ->build();
        }

        return HateoasBuilder::create()
            ->setUrlGenerator(null, new SymfonyUrlGenerator($urlGenerator))
            ->setDebug($debugMode)
            ->build();
    }
}

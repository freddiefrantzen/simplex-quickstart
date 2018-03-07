<?php declare(strict_types = 1);

namespace Simplex\Quickstart\Shared\Factory;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\FilesystemCache;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ValidatorFactory
{
    private const VALIDATOR_CACHE_DIRECTORY = 'validator';

    public function create(bool $enableCache, \SplFileInfo $cacheDir): ValidatorInterface
    {
        $validationBuilder = Validation::createValidatorBuilder();

        if (!$enableCache) {
            return $validationBuilder
                ->enableAnnotationMapping()
                ->getValidator();
        }

        $cache = new FilesystemCache($cacheDir->getPathname() . DIRECTORY_SEPARATOR . self::VALIDATOR_CACHE_DIRECTORY);

        $annotationReader = new CachedReader(new AnnotationReader(), $cache);

        return $validationBuilder
            ->enableAnnotationMapping($annotationReader)
            ->getValidator();
    }
}

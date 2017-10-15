<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Testing;

trait ReflectionPropertyCapabilities
{
    private function setProperty($object, string $propertyName, $propertyValue): void
    {
        $refObject = new \ReflectionObject($object);

        $refProperty = $refObject->getProperty($propertyName);
        $refProperty->setAccessible(true);
        $refProperty->setValue($object, $propertyValue);
    }
}

<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Testing;

trait ReflectionPropertyCapabilities
{
    /**
     * @param mixed $object
     * @param string $propertyName
     * @param $propertyValue
     */
    private function setProperty($object, string $propertyName, $propertyValue)
    {
        $refObject = new \ReflectionObject($object);

        $refProperty = $refObject->getProperty($propertyName);
        $refProperty->setAccessible(true);
        $refProperty->setValue($object, $propertyValue);
    }
}

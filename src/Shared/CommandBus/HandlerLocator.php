<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\CommandBus;

use Interop\Container\ContainerInterface;
use League\Tactician\Handler\Locator\HandlerLocator as LocatorInterface;

class HandlerLocator implements LocatorInterface
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getHandlerForCommand($commandName)
    {
        $namespaceParts = explode('\\', $commandName);

        $className = array_pop($namespaceParts);
        array_pop($namespaceParts); // remove 'Command' namespace part

        $namespaceParts[] = 'CommandHandler';
        $namespaceParts[] = $className . 'Handler';

        $handlerName = implode('\\', $namespaceParts);

        $handler = $this->container->get($handlerName);

        return $handler;
    }
}

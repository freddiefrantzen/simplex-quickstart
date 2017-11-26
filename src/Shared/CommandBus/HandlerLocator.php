<?php declare(strict_types = 1);

namespace Simplex\Quickstart\Shared\CommandBus;

use League\Tactician\Handler\Locator\HandlerLocator as LocatorInterface;
use Psr\Container\ContainerInterface;

class HandlerLocator implements LocatorInterface
{
    const COMMAND_HANDLER_NAMESPACE_PART = 'CommandHandler';
    const COMMAND_HANDLER_CLASS_SUFFIX = 'Handler';

    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getHandlerForCommand($commandName)
    {
        $namespaceParts = explode('\\', $commandName);

        $commandClassName = array_pop($namespaceParts);
        array_pop($namespaceParts); // remove 'Command' namespace part

        $namespaceParts[] = self::COMMAND_HANDLER_NAMESPACE_PART;
        $namespaceParts[] = $commandClassName . self::COMMAND_HANDLER_CLASS_SUFFIX;

        $handlerName = implode('\\', $namespaceParts);

        return $this->container->get($handlerName);
    }
}

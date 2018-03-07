<?php declare(strict_types = 1);

namespace Simplex\Quickstart\Shared\Factory;

use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;

final class CommandBusFactory
{
    public function create(HandlerLocator $locator)
    {
        $inflector = new HandleInflector();

        $nameExtractor = new ClassNameExtractor();

        $commandHandlerMiddleware = new CommandHandlerMiddleware($nameExtractor, $locator, $inflector);

        return new CommandBus([$commandHandlerMiddleware]);
    }
}

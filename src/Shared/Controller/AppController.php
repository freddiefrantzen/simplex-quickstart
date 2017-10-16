<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Controller;

use League\Tactician\CommandBus;
use Psr\Http\Message\ServerRequestInterface as Request;
use Simplex\Controller;

abstract class AppController extends Controller
{
    /** @var CommandBus */
    private $commandBus;

    public function setCommandBus(CommandBus $commandBus): void
    {
        $this->commandBus = $commandBus;
    }

    public function map(Request $request, string $commandClass)
    {
        return $this->serializer->deserialize((string) $request->getBody(), $commandClass, parent::JSON_FORMAT);
    }

    public function handleCommand($command): void
    {
        $this->commandBus->handle($command);
    }
}

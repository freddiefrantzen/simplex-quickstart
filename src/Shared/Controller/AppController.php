<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Controller;

use Simplex\Controller;
use League\Tactician\CommandBus;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class AppController extends Controller
{
    /** @var CommandBus */
    private $commandBus;

    public function setCommandBus(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function map(Request $request, $command)
    {
        $mapper = new \JsonMapper();

        $stdObject = json_decode((string) $request->getBody());

        $command = $mapper->map($stdObject, $command);

        return $command;
    }

    public function handleCommand($command)
    {
        $this->commandBus->handle($command);
    }
}

<?php declare(strict_types = 1);

namespace Simplex\Quickstart\Shared\Controller;

use JMS\Serializer\Exception\Exception as SerializerException;
use League\Tactician\CommandBus;
use Lukasoppermann\Httpstatus\Httpstatuscodes;
use Psr\Http\Message\ServerRequestInterface as Request;
use Simplex\BaseController;
use Simplex\Quickstart\Shared\Exception\BadRequestException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AppController extends BaseController
{
    /** @var CommandBus */
    private $commandBus;

    /** @var ValidatorInterface */
    private $validator;

    public function setCommandBus(CommandBus $commandBus): void
    {
        $this->commandBus = $commandBus;
    }

    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    /** @return mixed */
    public function map(Request $request, string $commandClass)
    {
        $command = $this->deserializeRequest($request, $commandClass);

        $this->validateRequest($command);

        return $command;
    }

    public function handleCommand($command): void
    {
        $this->commandBus->handle($command);
    }

    /** @return mixed */
    private function deserializeRequest(Request $request, string $commandClass)
    {
        try {
            $command = $this->serializer->deserialize((string) $request->getBody(), $commandClass, parent::JSON_FORMAT);
        } catch (SerializerException $exception) {
            throw new BadRequestException("Invalid request body", Httpstatuscodes::HTTP_BAD_REQUEST, $exception);
        }

        return $command;
    }

    /** @param mixed $command */
    private function validateRequest($command): void
    {
        $violations = $this->validator->validate($command);

        if ($violations->count() > 0) {

            $exception = new BadRequestException("Validation failure", Httpstatuscodes::HTTP_BAD_REQUEST);
            $exception->setViolations($violations);

            throw $exception;
        }
    }
}

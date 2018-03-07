<?php declare(strict_types = 1);

namespace Simplex\Quickstart\Shared\HttpMiddleware;

use JMS\Serializer\SerializerInterface;
use Lukasoppermann\Httpstatus\Httpstatuscodes;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplex\Exception\ResourceNotFoundException;
use Simplex\Quickstart\Shared\Exception\BadRequestException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class HandleBadRequests
{
    private const JSON_FORMAT = 'json';

    private const PROPERTY_KEY = 'property';
    private const MESSAGE_KEY = 'message';

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        try {

            $response = $next($request, $response);

        } catch (BadRequestException $exception) {

            $response = $response->withStatus(Httpstatuscodes::HTTP_BAD_REQUEST);
            $this->writeViolationsToResponse($exception, $response);

        } catch (ResourceNotFoundException $exception) {

            $response = $response->withStatus(Httpstatuscodes::HTTP_NOT_FOUND);
        }

        return $next($request, $response);
    }

    private function writeViolationsToResponse(BadRequestException $exception, ResponseInterface $response): void
    {
        $violations = $exception->getViolations();

        if (null === $violations) {
            return;
        }

        $response->getBody()->write(
            $this->serializer->serialize($this->createResponseBody($violations), self::JSON_FORMAT)
        );
    }

    private function createResponseBody(ConstraintViolationListInterface $violations): array
    {
        $body = [];

        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $body[] = [
                self::PROPERTY_KEY => $violation->getPropertyPath(),
                self::MESSAGE_KEY => $violation->getMessage(),
            ];
        }

        return $body;
    }
}

<?php declare(strict_types=1);

namespace Simplex\Quickstart\Module\Demo\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Simplex\Quickstart\Module\Demo\Command\Register;
use Simplex\Quickstart\Module\Demo\Repository\PersonRepository;
use Simplex\Quickstart\Shared\Controller\AppController;
use Symfony\Component\Routing\Annotation\Route;

class DemoController extends AppController
{
    /** @var PersonRepository */
    private $personRepository;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * @Route("/", methods={"GET"}, name="list_people")
     */
    public function index(Request $request, Response $response): Response
    {
        $people = $this->personRepository->findAll();

        return $this->jsonResponse($response, $people);
    }

    /**
     * @Route("/person/{id}", methods={"GET"}, name="get_person")
     */
    public function get(Request $request, Response $response, $id): Response
    {
        $person = $this->personRepository->ofId($id);

        return $this->jsonResponse($response, $person);
    }

    /**
     * @Route("/person", methods={"POST"}, name="register")
     */
    public function post(Request $request, Response $response): Response
    {
        $command = $this->map($request, Register::class);

        $this->handleCommand($command);

        $person = $this->personRepository->ofUsername($command->email);

        return $this->createResponse($response, $person->getId()->toString());
    }
}

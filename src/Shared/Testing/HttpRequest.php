<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Testing;

use Simplex\HttpApplication;
use Simplex\Kernel;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Stream;
use Zend\Diactoros\Uri;

class HttpRequest
{
    /** @var Kernel */
    private $kernel;

    /** @var ServerRequestInterface */
    private $request;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
        $this->request = $this->createRequest();

        return $this;
    }

    private function createRequest(): ServerRequestInterface
    {
        return ServerRequestFactory::fromGlobals([]);
    }

    public function withHeader(string $header, string $value): HttpRequest
    {
        $this->request = $this->request->withHeader($header, $value);

        return $this;
    }

    public function sendGet(string $uri, array $queryParams = [], $headers = []): ResponseInterface
    {
        $this->request = $this->request
            ->withUri(new Uri($uri))
            ->withQueryParams($queryParams)
            ->withMethod('GET');

        $response = $this->send();

        return $response;
    }

    public function sendPost(string $uri,  array $body): ResponseInterface
    {
        $stream = $this->streamFor($body);

        $this->request = $this->request
            ->withUri(new Uri($uri))
            ->withBody($stream)
            ->withMethod('POST');

        $response = $this->send();

        return $response;
    }

    public function sendPut(string $uri,  array $body): ResponseInterface
    {
        $stream = $this->streamFor($body);

        $this->request = $this->request
            ->withUri(new Uri($uri))
            ->withBody($stream)
            ->withMethod('POST');

        $response = $this->send();

        return $response;
    }

    public function sendDelete(string $uri): ResponseInterface
    {
        $this->request = $this->request
            ->withUri(new Uri($uri))
            ->withMethod('DELETE');

        $response = $this->send();

        return $response;
    }

    private function send(): ResponseInterface
    {
        $application = new HttpApplication($this->kernel);

        $response = $application->handleRequest($this->request, new Response());

        return $response;
    }

    /**
     * @see https://github.com/guzzle/psr7/blob/master/src/functions.php
     */
    private function streamFor(array $body): Stream
    {
        $resource = json_encode($body);

        $stream = fopen('php://temp', 'r+');
        if ($resource !== '') {
            fwrite($stream, $resource);
            fseek($stream, 0);
        }

        return new Stream($stream);
    }
}

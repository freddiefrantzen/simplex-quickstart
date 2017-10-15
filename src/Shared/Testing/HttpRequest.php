<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Testing;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplex\HttpApplication;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Stream;
use Zend\Diactoros\Uri;

class HttpRequest
{
    const HTTP_GET = 'GET';
    const HTTP_POST = 'POST';
    const HTTP_PUT = 'PUT';
    const HTTP_DELETE = 'DELETE';

    /** @var Container */
    private $container;

    /** @var ServerRequestInterface */
    private $request;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->request = $this->createRequest();
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
            ->withMethod(self::HTTP_GET);

        return $this->send();
    }

    public function sendPost(string $uri,  array $body): ResponseInterface
    {
        $stream = $this->streamFor($body);

        $this->request = $this->request
            ->withUri(new Uri($uri))
            ->withBody($stream)
            ->withMethod(self::HTTP_POST);

        return $this->send();
    }

    public function sendPut(string $uri,  array $body): ResponseInterface
    {
        $stream = $this->streamFor($body);

        $this->request = $this->request
            ->withUri(new Uri($uri))
            ->withBody($stream)
            ->withMethod(self::HTTP_PUT);

        return $this->send();
    }

    public function sendDelete(string $uri): ResponseInterface
    {
        $this->request = $this->request
            ->withUri(new Uri($uri))
            ->withMethod(self::HTTP_DELETE);

        return $this->send();
    }

    private function send(): ResponseInterface
    {
        $application = new HttpApplication($this->container);

        return $application->handleRequest($this->request, new Response());
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

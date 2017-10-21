<?php declare(strict_types=1);

namespace Simplex\Quickstart\Shared\Testing;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplex\HttpApplication;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Stream;
use Zend\Diactoros\Uri;
use Psr\Http;

trait HttpRequestCapabilities
{
    public function sendGet(string $uri, array $queryParams = [], array $headers = []): ResponseInterface
    {
        $request = $this->createRequest('GET', $uri)
            ->withQueryParams($queryParams);

        $request = $this->addHeaders($headers, $request);

        return $this->send($request);
    }

    private function createRequest(string $method, string $uri): ServerRequestInterface
    {
        return (ServerRequestFactory::fromGlobals([]))
            ->withMethod($method)
            ->withUri(new Uri($uri));
    }

    private function addHeaders(array $headers, ServerRequestInterface $request): ServerRequestInterface
    {
        if (empty($headers)) {
            return $request;
        }

        foreach ($headers as $headerName => $headerValue) {
            $request = $request->withHeader($headerName, $headerValue);
        }

        return $request;
    }

    private function send(ServerRequestInterface $request): ResponseInterface
    {
        $application = new HttpApplication($this->getContainer());

        return $application->handleRequest($request, new Response());
    }

    public function sendPost(string $uri, array $body, array $headers = []): ResponseInterface
    {
        $stream = $this->streamFor($body);

        $request = $this->createRequest('POST', $uri)
            ->withBody($stream);

        $request = $this->addHeaders($headers, $request);

        return $this->send($request);
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

    public function sendPut(string $uri, array $body, array $headers = []): ResponseInterface
    {
        $stream = $this->streamFor($body);

        $request = $this->createRequest('PUT', $uri)
            ->withBody($stream);

        $request = $this->addHeaders($headers, $request);

        return $this->send($request);
    }

    public function sendDelete(string $uri, array $headers = []): ResponseInterface
    {
        $request = $this->createRequest('DELETE', $uri);

        $request = $this->addHeaders($headers, $request);

        return $this->send($request);
    }

    public function getBody(ResponseInterface $response): array
    {
        return json_decode((string) $response->getBody(), true);
    }

    public function getRawBody(ResponseInterface $response): string
    {
        return (string) $response->getBody();
    }
}

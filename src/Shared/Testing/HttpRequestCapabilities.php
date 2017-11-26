<?php declare(strict_types = 1);

namespace Simplex\Quickstart\Shared\Testing;

use function GuzzleHttp\Psr7\stream_for;
use Psr\Container\ContainerInterface;
use Psr\Http;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Simplex\HttpApplication;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Uri;

trait HttpRequestCapabilities
{
    abstract public function getContainer(): ContainerInterface;

    public function sendGet(string $uri, array $queryParams = [], array $headers = []): ResponseInterface
    {
        $request = $this->createRequest('GET', $uri, $headers)
            ->withQueryParams($queryParams);

        return $this->send($request);
    }

    private function createRequest(string $method, string $uri, array $headers = []): ServerRequestInterface
    {
        $request = (ServerRequestFactory::fromGlobals([]))
            ->withMethod($method)
            ->withUri(new Uri($uri));

        if (empty($headers)) {
            return $request;
        }

        return $this->addHeaders($headers, $request);
    }

    private function addHeaders(array $headers, ServerRequestInterface $request): ServerRequestInterface
    {
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
        $stream = stream_for(json_encode($body));

        $request = $this->createRequest('POST', $uri, $headers)
            ->withBody($stream);

        return $this->send($request);
    }

    public function sendPut(string $uri, array $body, array $headers = []): ResponseInterface
    {
        $stream = stream_for(json_encode($body));

        $request = $this->createRequest('PUT', $uri, $headers)
            ->withBody($stream);

        return $this->send($request);
    }

    public function sendDelete(string $uri, array $headers = []): ResponseInterface
    {
        $request = $this->createRequest('DELETE', $uri, $headers);

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

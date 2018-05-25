<?php

namespace Omneo\Modules;

use GuzzleHttp;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Proxy extends Module
{
    /**
     * Proxy a request.
     *
     * @param RequestInterface $request
     * @param null $normalisedPath
     * @return ResponseInterface
     */
    public function send(RequestInterface $request, $normalisedPath = null)
    {
        // Create URI from configured endpoint.
        $endpoint = $this->client->getUri();

        // Initialise our new URI with configured host.
        $uri = $request->getUri()
            ->withScheme($endpoint->getScheme())
            ->withPort($endpoint->getPort())
            ->withHost($endpoint->getHost());

        if ($normalisedPath) {
            $uri = $uri->withPath($normalisedPath);
        }

        if ($endpoint->getPath()) {
            $uri = $uri->withPath($endpoint->getPath().$uri->getPath());
        }

        try {
            return $this->parseProxyResponse(
                $this->client->send($request->withUri($uri))
            );
        } catch (GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                return $this->parseProxyResponse($e->getResponse());
            }

            throw $e;
        }
    }

    /**
     * Parse upstream proxy response before passing downstream.
     *
     * Here, we remove irrelevant upstream headers. If we pass these down, they may no longer match actual response
     * characteristics (e.g. transfer-encoding chunked) and will cause browser problems.
     *
     * @param  ResponseInterface $response
     * @return ResponseInterface
     */
    protected function parseProxyResponse(ResponseInterface $response)
    {
        return $response
            ->withoutHeader('transfer-encoding')
            ->withoutHeader('connection')
            ->withoutHeader('server')
            ->withoutHeader('date');
    }
}

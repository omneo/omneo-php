<?php

namespace Omneo;

use GuzzleHttp;
use Psr\Http\Message\RequestInterface;

class Client
{
    use Modules\BuildsModules;

    /**
     * Omneo domain.
     *
     * For example `client.omneoapp.com`
     *
     * @var string
     */
    protected $domain;

    /**
     * Omneo bearer token.
     *
     * @var string
     */
    protected $token;

    /**
     * Guzzle client for HTTP transport.
     *
     * @var GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * Client constructor.
     *
     * @param string $domain
     * @param string $token
     */
    public function __construct($domain, $token)
    {
        $this->domain = $domain;
        $this->token = $token;

        $this->setupClient();
    }

    /**
    * Get Omneo domain.
    *
    * @return string
    */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Get Omneo bearer token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Pass unknown methods off to the underlying Guzzle client.
     *
     * @param  string $name
     * @param  array  $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->client, $name], $arguments);
    }

    /**
     * Setup Guzzle client with optional provided handler stack.
     *
     * @param  GuzzleHttp\HandlerStack|null $stack
     * @param  array                        $options
     * @return Client
     */
    public function setupClient(GuzzleHttp\HandlerStack $stack = null, $options = [])
    {
        $stack = $stack ?: GuzzleHttp\HandlerStack::create();

        $this->bindHeadersMiddleware($stack);

        $this->client = new GuzzleHttp\Client(array_merge([
            'handler'  => $stack,
            'base_uri' => 'https://'.$this->domain.'/api/v3/'
        ], $options));

        return $this;
    }

    /**
     * Bind outgoing request middleware for headers.
     *
     * @param  GuzzleHttp\HandlerStack $stack
     * @return void
     */
    protected function bindHeadersMiddleware(GuzzleHttp\HandlerStack $stack)
    {
        $stack->push(GuzzleHttp\Middleware::mapRequest(function (RequestInterface $request) {
            return $request
                ->withHeader('Accept', 'application/json')
                ->withHeader('Authorization', sprintf('Bearer %s', $this->token));
        }));
    }
}

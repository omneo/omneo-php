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
     * Omneo shared secret.
     *
     * This is used for verifying inbound webhooks and targets.
     *
     * @var string
     */
    protected $secret;

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
    public function __construct(string $domain, string $token)
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
     * Get the URL.
     *
     * @return GuzzleHttp\Psr7\Uri
     */
    public function getUrl()
    {
        return new GuzzleHttp\Psr7\Uri(
            sprintf('http://%s/api/v3/', $this->domain)
        );
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
     * Get Omneo shared secret.
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set Omneo shared secret.
     *
     * @param  string  $secret
     * @return static
     */
    public function setSecret(string $secret)
    {
        $this->secret = $secret;

        return $this;
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
    public function setupClient(GuzzleHttp\HandlerStack $stack = null, array $options = [])
    {
        $stack = $stack ?: GuzzleHttp\HandlerStack::create();

        $this->bindHeadersMiddleware($stack);

        $this->client = new GuzzleHttp\Client(array_merge([
            'handler'  => $stack,
            'base_uri' => (string) $this->getUrl()
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

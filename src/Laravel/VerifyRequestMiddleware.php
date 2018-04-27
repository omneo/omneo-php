<?php

namespace Omneo\Laravel;

use Omneo;
use Illuminate\Http\Request;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VerifyRequestMiddleware
{
    /**
     * Omneo client.
     *
     * @var Omneo\Client
     */
    protected $omneo;

    /**
     * VerifyRequestMiddleware constructor.
     *
     * @param  Omneo\Client  $omneo
     */
    public function __construct(Omneo\Client $omneo)
    {
        $this->omneo = $omneo;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        $request = (new DiactorosFactory)->createRequest($request);

        try {
            $this->omneo->requestVerifier()->verify($request);
        } catch (Omneo\Exceptions\RequestVerificationException $e) {
            throw new HttpException(401, 'Unauthorized', $e);
        }

        return $next($request);
    }
}

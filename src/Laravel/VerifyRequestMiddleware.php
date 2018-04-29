<?php

namespace Omneo\Laravel;

use Omneo;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

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
        try {
            $this->omneo->requestVerifier()->verify(
                (new DiactorosFactory)->createRequest($request)
            );
        } catch (Omneo\Exceptions\RequestVerificationException $e) {
            throw new HttpException(401, 'Unauthorized', $e);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;

class VerifyRequestMiddlware
{
    /**
     * Handle an incoming request.
     *
     * @param  ServerRequestInterface  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(ServerRequestInterface $request, \Closure $next)
    {
        dd($request);

        return $next($request);
    }
}

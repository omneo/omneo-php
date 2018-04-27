<?php

namespace Omneo\Modules;

use Omneo\Exceptions;
use GuzzleHttp\Psr7\Request;

class RequestVerifier extends Module
{
    /**
     * Verify the given request.
     *
     * @param  Request  $request
     * @return true
     * @throws Exceptions\RequestVerificationException
     */
    public function verify(Request $request)
    {
        if (! $remoteSignature = $request->getHeaderLine('X-Omneo-Hmac-SHA256')) {
            throw new Exceptions\RequestVerificationException('No signature provided via `X-Omneo-Hmac-SHA256` header');
        }

        $localSignature = hash_hmac('sha256', (string) $request->getBody(), $this->client->getSecret());

        if (! hash_equals($localSignature, $remoteSignature)) {
            throw new Exceptions\RequestVerificationException('Signature provided via `X-Omneo-Hmac-SHA256` could not be verified, check your shared secret');
        }

        return true;
    }
}
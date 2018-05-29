<?php

namespace Omneo\Concerns;

trait InteractsWithUris
{
    /**
     * Prepare a URI.
     *
     * @param string $uri
     * @return string
     */
    protected function prepareUri(string $uri)
    {
        // Strip leading slashes. This can cause issues as a leading
        // slash will remove the base API path from the domain.
        return ltrim($uri, '/');
    }
}

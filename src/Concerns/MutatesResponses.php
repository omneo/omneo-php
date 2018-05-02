<?php

namespace Omneo\Concerns;

use Omneo;
use Illuminate\Support\Arr;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;

trait MutatesResponses
{
    /**
     * Transform the given entity with the given transformer.
     *
     * @param  array  $data
     * @param  string|callable  $transformer
     * @return mixed
     */
    protected function transformEntity(array $data, $transformer)
    {
        if (! is_callable($transformer) && class_exists($transformer)) {
            $transformer = function (array $data) use ($transformer) {
                return new $transformer($data);
            };
        }

        return $transformer($data);
    }

    /**
     * Build an entity from the given response.
     *
     * @param  Response  $response
     * @param  string|callable  $transformer
     * @return mixed
     */
    protected function buildEntity(Response $response, $transformer)
    {
        return $this->transformEntity(
            json_decode((string) $response->getBody(), true)['data'],
            $transformer
        );
    }

    /**
     * Build a collection from the given response.
     *
     * @param  Response  $response
     * @param  string|callable  $transformer
     * @return Collection
     */
    protected function buildCollection(Response $response, $transformer)
    {
        return (new Collection(
            json_decode((string) $response->getBody(), true)['data']
        ))->map(function (array $row) use ($transformer) {
            return $this->transformEntity($row, $transformer);
        });
    }

    /**
     * Build a paginated collection from the given response.
     *
     * @param  Response  $response
     * @param  string|callable  $transformer
     * @param  callable  $executor
     * @param  Omneo\Constraint  $constraint
     * @return Omneo\PaginatedCollection
     */
    protected function buildPaginatedCollection(
        Response $response,
        $transformer,
        callable $executor = null,
        Omneo\Constraint $constraint = null
    ) {
        $meta = json_decode((string) $response->getBody(), true)['meta'];

        return new Omneo\PaginatedCollection(
            $this->buildCollection($response, $transformer),
            Arr::get($meta, 'current_page'),
            Arr::get($meta, 'last_page'),
            Arr::get($meta, 'per_page'),
            Arr::get($meta, 'total'),
            $executor,
            $constraint
        );
    }
}
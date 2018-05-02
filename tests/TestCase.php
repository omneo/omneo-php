<?php

namespace Omneo;

use Mockery;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /**
     * Return stub at the given path.
     *
     * @param  string  $path
     * @return string
     */
    protected function stub(string $path)
    {
        return file_get_contents(__DIR__.'/stubs/'.$path);
    }

    /**
     * Return decoded JSON stub at the given path.
     *
     * @param  string  $path
     * @return array
     */
    protected function jsonStub(string $path)
    {
        return json_decode(
            file_get_contents(__DIR__.'/stubs/'.$path),
            true
        );
    }
}
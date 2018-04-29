<?php

namespace Omneo;

use Omneo\Concerns;
use Illuminate\Support\Fluent;

abstract class Entity extends Fluent
{
    use Concerns\HasAttributes,
        Concerns\ValidatesAttributes;
}

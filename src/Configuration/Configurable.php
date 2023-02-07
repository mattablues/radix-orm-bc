<?php

declare(strict_types=1);

namespace Radix\Configuration;

interface Configurable
{
    public function get(string $key): mixed;
}
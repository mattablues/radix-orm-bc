<?php

declare(strict_types=1);

namespace Radix\Config;

interface Configurable
{
    public function get(string $key): mixed;
}
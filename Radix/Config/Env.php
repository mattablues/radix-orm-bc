<?php

declare(strict_types=1);


namespace Radix\Config;

class Env implements Configurable
{
    public function get(string $key): mixed
    {
        $key = strtoupper($key);

        if (array_key_exists($key, $_ENV)) {
            return $_ENV[$key];
        }

        return null;
    }

    public function set(string $key, mixed $value): void
    {
        $key = strtoupper($key);

        $_ENV[$key] = $value;
    }
}
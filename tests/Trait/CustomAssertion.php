<?php

declare(strict_types=1);

namespace App\Tests\Trait;

class CustomAssertion
{
    public function assertArrayHasKeys(array $array, array $keys): void
    {
        $diff = array_diff($keys, array_keys($array));

        $this->assertTrue(!$diff, 'The array does not have the following key(s): ' . implode(', ', $diff));
    }
}
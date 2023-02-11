<?php

declare(strict_types=1);

namespace Radix\Config;

use Radix\Config\Exception\ConfigInvalidArrayKeyException;
use Radix\Utility\ArrayFileExtractor;
use Radix\Utility\Exception\ArrayFileHasInvalidKey;
use Radix\Utility\Exception\ArrayFileInvalid;
use Radix\Utility\Exception\ArrayFileNotFoundException;

final class Config extends ArrayFileExtractor implements Configurable
{
protected string $directory = 'config';
    /**
     * @throws ArrayFileNotFoundException
     * @throws ArrayFileHasInvalidKey
     * @throws ArrayFileInvalid
     */
    public function __construct(string $files, ?string $directory = null)
    {
        if ($directory) {
            $this->directory = $directory;
        }

        $this->extract = $files;
        $this->contents = $this->getContent();
    }

    /**
     * @throws ConfigInvalidArrayKeyException
     */
    public function get(string $key = null): mixed
    {
        if ($key) {
            if (!array_key_exists($key, $this->contents)) {
                throw new ConfigInvalidArrayKeyException("Array key: $key does not exist in array.");
            }

            return $this->contents[$key];
        }

        return $this->contents;
    }
}
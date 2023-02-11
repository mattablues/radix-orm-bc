<?php

declare(strict_types=1);

namespace Radix\Utility;

use Radix\Utility\Exception\ArrayFileHasInvalidKey;
use Radix\Utility\Exception\ArrayFileInvalid;
use Radix\Utility\Exception\ArrayFileNotFoundException;

abstract class ArrayFileExtractor
{
protected string $directory;
    protected array $contents = [];
    protected string $extract;
    private string $path;
    private const  PATH_LEVEL = 2;

    abstract public function get(string $key = null): mixed;

    private function setPath(): void
    {
        $this->path = dirname(__DIR__, self::PATH_LEVEL) . '/' . $this->directory . '/';
    }

    /**
     * @throws ArrayFileHasInvalidKey
     * @throws ArrayFileNotFoundException
     * @throws ArrayFileInvalid
     */
    protected function getContent(): array
    {
        return $this->handleContents();
    }

    /**
     * @throws ArrayFileNotFoundException
     * @throws ArrayFileInvalid
     * @throws ArrayFileHasInvalidKey
     */
    private function handleContents(): array
    {
        $this->setPath();
        $files = explode('.', $this->extract);


        foreach ($files as $field) {
            $filename = $this->path . $field . '.php';

            if (file_exists($filename)) {
                $file = require $filename;

                if (!is_array($file) || empty($file)) {
                    throw new ArrayFileInvalid( "File: $file has no array or file is empty.");
                }

                foreach ($file as $key => $value) {
                    if (array_key_exists($key, $this->contents)) {
                        throw new ArrayFileHasInvalidKey("Invalid array key: $key already exists.");
                    }

                    if (is_array($value)) {
                        for ($i = 0; $i < count($value); $i++) {
                            $newKey = $field . '.' . $key . '.' . array_keys($value)[$i];

                            $this->contents[$newKey] = array_values($value)[$i];
                        }
                    } else {
                        if (!is_int($key)) {
                            $key = $field . '.' . $key;
                        }

                        $this->contents[$key] = $value;
                    }
                }
            } else {
                if (is_array($files)) {
                    $files = implode('.php, ', $files) . '.php';
                }

                throw new ArrayFileNotFoundException('One or more file(s) not found: ' . $files);
            }
        }

        return $this->contents;
    }
}
<?php

declare(strict_types=1);

use JetBrains\PhpStorm\NoReturn;
use Radix\Configuration\Env;

if(!function_exists('env')) {
    function env(string $key): mixed
    {
        $env = new Env();

        return $env->get($key);
    }
}

if(!function_exists('setenv')) {
    function setenv(string $key, mixed $value): void
    {
        $env = new Env();

        $env->set($key, $value);
    }
}

if (!function_exists('mb_ucwords')) {
    function mb_ucwords(string $string): array|bool|string|null
    {
        return mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
    }
}

if (!function_exists('is_assoc')) {
    function is_assoc(array $data): bool
    {
        $keys = array_keys($data);
        return $keys !== range(0, count($data) -1);
    }
}

if (!function_exists('get_object_public_fields')) {
    function get_object_public_fields(object $obj): array
    {
        return get_object_vars($obj);
    }
}

if (!function_exists('redirect')) {
    #[NoReturn] function redirect(string $url): void
    {
        header('Location: ' . $url, true, 303);
        exit();
    }
}

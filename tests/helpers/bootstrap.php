<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2), '.env.test');
$dotenv->load();

const URL = 'localhost';
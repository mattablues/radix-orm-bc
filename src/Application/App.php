<?php

declare(strict_types=1);

namespace Radix\Application;

use Radix\Configuration\ConfigInterface;

class App
{
    public function __construct(
        protected ConfigInterface $config
    ) {}

    public function handle(ConfigInterface $config) {}
}
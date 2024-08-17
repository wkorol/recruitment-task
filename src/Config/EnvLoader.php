<?php

declare(strict_types=1);

namespace src\Config;

use Dotenv\Dotenv;

class EnvLoader
{
    public static function load(): void
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->load();
    }
}
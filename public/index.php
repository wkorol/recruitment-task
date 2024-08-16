<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;
use App\Config\EnvLoader;

EnvLoader::load();
$db = new Database();




<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;
use App\Config\EnvLoader;
use App\Controller\LoginController;
use App\Services\UserRepository;

EnvLoader::load();
$database = new Database();
$userRepository = new UserRepository($database);
$loginController = new LoginController($userRepository);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($path) {
    case '/login':
        $loginController->login();
        break;
    case '/logout':
        $loginController->logout();
        break;
    case '/':
        echo 'TODO';
        break;
}
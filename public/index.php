<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;
use App\Config\EnvLoader;
use App\Controller\LoginController;
use App\Controller\NewsController;
use App\Services\NewsRepository;
use App\Services\UserRepository;

EnvLoader::load();
$database = new Database();
$userRepository = new UserRepository($database);
$newsRepository = new NewsRepository($database);
$loginController = new LoginController($userRepository);
$newsController = new NewsController($newsRepository);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function handleNewsRoutes(string $path, NewsController $newsController): void
{
    if (!preg_match('/^\/news(?:\/(\d+))?$/', $path, $matches)) {
        return;
    }

    $newsId = isset($matches[1]) ? (int)$matches[1] : null;

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($newsId === null) {
            $newsController->getAllNewses();
            return;
        }

        $newsController->getNewsById($newsId);
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $newsId !== null) {
        $newsController->deleteNewsById($newsId);
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $newsId === null) {
        $newsController->createNews();
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'PUT' && $newsId !== null) {
        $newsController->updateNews($newsId);
        return;
    }

    http_response_code(405);
    echo 'Method not allowed';
}

function handleLoginRoutes(string $path, LoginController $loginController): void
{
    if ($path === '/login') {
        $loginController->login();
        return;
    }

    if ($path === '/logout') {
        $loginController->logout();
    }
}

handleNewsRoutes($path, $newsController);
handleLoginRoutes($path, $loginController);

if ($path === '/') {
    echo 'TODO';
    return;
}
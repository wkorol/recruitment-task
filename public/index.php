<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\LoginController;
use App\Controller\NewsController;
use App\Services\JsonResponse;
use App\Services\News\NewsRepository;
use App\Services\User\UserRepository;
use src\Config\Database;
use src\Config\EnvLoader;

EnvLoader::load();

$database = new Database();
$userRepository = new UserRepository($database);
$newsRepository = new NewsRepository($database);
$loginController = new LoginController($userRepository);
$newsController = new NewsController($newsRepository);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

handleNewsRoutes($path, $newsController, $loginController) ||
handleLoginRoutes($path, $loginController) ||
redirectToLogin();

function handleNewsRoutes(string $path, NewsController $newsController, LoginController $loginController): ?JsonResponse
{
    $newsRegex = '/^\/news(?:\/(\d+))?$/';
    if (!$loginController->isLoggedIn() && preg_match($newsRegex, $path)) {
        return new JsonResponse(['error' => 'Unauthorized access'], 401);
    }
    if (!preg_match($newsRegex, $path, $matches)) {
        return null;
    }

    $newsId = isset($matches[1]) ? (int)$matches[1] : null;

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($newsId === null) {
            return $newsController->getAllNewses();
        }

        return $newsController->getNewsById($newsId);
    } if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $newsId !== null) {
       return $newsController->deleteNewsById($newsId);
    } if ($_SERVER['REQUEST_METHOD'] === 'POST' && $newsId === null) {
        return $newsController->createNews();
    } if ($_SERVER['REQUEST_METHOD'] === 'PUT' && $newsId !== null) {
        return $newsController->updateNews($newsId);
    }

    return null;
}

function handleLoginRoutes(string $path, LoginController $loginController): ?JsonResponse
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($path === '/login') {
            return $loginController->login();
        }

        if ($path === '/logout') {
            return $loginController->logout();
        }
    }

    if ($path === '/check-login-status' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        return new JsonResponse(['loggedIn' => $loginController->isLoggedIn()]);
    }

    return null;
}

function redirectToLogin(): void
{
    if (isset($_SESSION['login'])) {
        header('Location: /dashboard.php');
    }
    header('Location: /login.php');
}

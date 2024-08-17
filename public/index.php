<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\LoginController;
use App\Controller\NewsController;
use App\Services\NewsRepository;
use App\Services\UserRepository;
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

function handleNewsRoutes(string $path, NewsController $newsController, LoginController $loginController): bool
{
    $newsRegex = '/^\/news(?:\/(\d+))?$/';
    if (!$loginController->isLoggedIn() && preg_match($newsRegex, $path)) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized access']);
        return true;
    }
    if (!preg_match($newsRegex, $path, $matches)) {
        return false;
    }

    $newsId = isset($matches[1]) ? (int)$matches[1] : null;

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($newsId === null) {
            $newsController->getAllNewses();
        } else {
            $newsController->getNewsById($newsId);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $newsId !== null) {
        $newsController->deleteNewsById($newsId);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $newsId === null) {
        $newsController->createNews();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && $newsId !== null) {
        $newsController->updateNews($newsId);
    } else {
        http_response_code(405);
        echo 'Method not allowed';
    }

    return true;
}

function handleLoginRoutes(string $path, LoginController $loginController): bool
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($path === '/login') {
            $loginController->login();
            return true;
        }

        if ($path === '/logout') {
            $loginController->logout();
            return true;
        }
    }

    if ($path === '/check-login-status' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        header('Content-Type: application/json');
        echo json_encode(['loggedIn' => $loginController->isLoggedIn()]);
        return true;
    }

    return false;
}

function redirectToLogin(): void
{
    if (isset($_SESSION['login'])) {
        header('Location: /dashboard.php');
    }
    header('Location: /login.php');
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\JsonResponse;
use App\Services\User\UserRepositoryInterface;
use PDOException;

readonly class LoginController
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
        session_start();
    }

    public function login(): JsonResponse
    {
        if ($this->isLoggedIn()) {
            return new JsonResponse(['error' => 'You are already logged in.'], 400);
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['login'] ?? null;
            $password = $_POST['password'] ?? null;

            try {
                $user = $this->userRepository->getUserByLogin($login);
            } catch (PDOException $e) {
                return new JsonResponse(['error' => $e->getMessage()], 500);
            }

            if ($user && password_verify($password, $user->getPassword())) {
                $_SESSION['login'] = $user->getLogin();
                setcookie('session_id', session_id(), time() + 3600, '/', '', false, true);
                return new JsonResponse(
                    ['message' => 'Login successful']
                );
            }
            return new JsonResponse(
                ['error' => 'Wrong login data.'],
                401
            );
        }

        return JsonResponse::wrongRequestMethod($_SERVER['REQUEST_METHOD']);
    }

    public function logout(): JsonResponse
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->isLoggedIn()) {
                session_unset();
                session_destroy();

                setcookie('session_id', '', time() - 3600, '/');

                return new JsonResponse(['message' => 'Logout successful']);
            }
            return new JsonResponse(['error' => 'Nobody is logged in'], 400);
        }
        return JsonResponse::wrongRequestMethod($_SERVER['REQUEST_METHOD']);
    }

    public function isLoggedIn(): bool
    {
        if (isset($_SESSION['login'])) {
            return true;
        }

        if (isset($_COOKIE['session_id']) && $_COOKIE['session_id'] === session_id()) {
            return true;
        }

        return false;
    }
}
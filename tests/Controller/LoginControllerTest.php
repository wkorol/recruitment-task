<?php

declare(strict_types=1);

namespace Controller;

use App\Controller\LoginController;
use App\Dto\User;
use App\Services\UserRepository;
use PHPUnit\Framework\TestCase;

class LoginControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        $_SESSION = [];
        session_unset();
        session_destroy();

        parent::tearDown();
    }
    public function testLoginSuccessful(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);

        $user = new User(1, 'admin', password_hash('test', PASSWORD_DEFAULT));

        $userRepositoryMock->method('getUserByLogin')
            ->with('admin')
            ->willReturn($user);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['login'] = 'admin';
        $_POST['password'] = 'test';

        $loginController = new LoginController($userRepositoryMock);

        $response = $loginController->login();

        $this->assertStringContainsString('Login successful', $response->getMessage());

    }

    public function testLoginFailed(): void
    {
        $userRepositoryMock = $this->createMock(UserRepository::class);

        $userRepositoryMock->method('getUserByLogin')
            ->with('admin')
            ->willReturn(null);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['login'] = 'admin';
        $_POST['password'] = 'wrongpassword';

        $loginController = new LoginController($userRepositoryMock);

        $response = $loginController->login();

        $this->assertEquals('Wrong login data.', $response->getError());
    }
}
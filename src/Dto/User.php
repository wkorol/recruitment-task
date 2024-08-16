<?php

declare(strict_types=1);

namespace App\Dto;

readonly class User
{
    public function __construct(
        private int $id,
        private string $login,
        private string $password
    ) {
    }

    public function getId(): int {
        return $this->id;
    }
    public function getLogin(): string {
        return $this->login;
    }
    public function getPassword(): string {
        return $this->password;
    }
}
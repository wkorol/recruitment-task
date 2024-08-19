<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Dto\User;

interface UserRepositoryInterface
{
    public function getUserByLogin(?string $login): ?User;
}
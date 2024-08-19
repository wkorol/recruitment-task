<?php

declare(strict_types=1);

namespace App\Services\News;

use App\Dto\News;

interface NewsRepositoryInterface
{
    public function getNewsById(int $id): ?News;
    public function createNews(string $title, string $description): int;
    public function updateNews(int $id, string $title, string $description): int;
}
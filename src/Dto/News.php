<?php

declare(strict_types=1);

namespace App\Dto;

readonly class News implements \JsonSerializable
{
    public function __construct(
        private int    $id,
        private string $title,
        private string $description
    ) {}

    public function getId(): int
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
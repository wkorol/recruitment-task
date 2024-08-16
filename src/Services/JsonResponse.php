<?php

declare(strict_types=1);

namespace App\Services;

class JsonResponse
{
    private ?string $message = null;
    private ?string $error = null;

    public function __construct(private array $data, private int $statusCode = 200)
    {
        $this->extractMessagesAndErrors($data);
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
    }

    private function extractMessagesAndErrors(array $data): void
    {
        $this->message = $data['message'] ?? null;
        $this->error = $data['error'] ?? null;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getData(): array
    {
        return $this->data ?? [];
    }
}
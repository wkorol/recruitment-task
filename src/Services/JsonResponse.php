<?php

declare(strict_types=1);

namespace App\Services;

use JsonSerializable;

class JsonResponse
{
    private ?string $message = null;
    private ?string $error = null;

    /**
     * @param string|string[] $data
     */
    public function __construct(private mixed $data, private readonly int $statusCode = 200)
    {
        $this->extractMessagesAndErrors(json_encode($data));
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo $this->parseData($data);
    }

    /**
     * @param string|string[] $data
     */
    private function parseData(mixed $data = []): string
    {
        $this->data = match (true) {
            $data instanceof JsonSerializable => json_encode($data->jsonSerialize(), JSON_PRETTY_PRINT),
            default => json_encode($data, JSON_PRETTY_PRINT),
        };
        return $this->data;
    }

    /**
     * @param string|string[] $data
     */
    private function extractMessagesAndErrors(mixed $data): void
    {
        if (is_string($data)) {
            $decodedData = json_decode($data, true);


            if (json_last_error() === JSON_ERROR_NONE) {
                $data = $decodedData;
            } else {
                $data = [];
            }
        }

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

    public static function wrongRequestMethod(string $requestMethod): self
    {
        return new self(['error' => $requestMethod . ' method not allowed'], 405);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string|string[]
     */
    public function getData(): mixed
    {
        return $this->data ?? [];
    }
}
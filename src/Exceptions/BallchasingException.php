<?php

namespace Lucie\BallchasingLaravel\Exceptions;

use Exception;

class BallchasingException extends Exception
{
    public static function apiRequestFailed(string $message, int $statusCode = 0): self
    {
        return new self("API request failed: {$message}", $statusCode);
    }

    public static function invalidResponse(string $reason = 'Invalid response format'): self
    {
        return new self("Failed to decode API response: {$reason}");
    }

    public static function fileNotFound(string $filePath): self
    {
        return new self("File not found: {$filePath}");
    }

    public static function invalidApiKey(): self
    {
        return new self('Invalid or missing API key');
    }
}
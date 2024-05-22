<?php

declare(strict_types=1);

class Session
{
    public function ensureStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function get(string $key): mixed
    {
        $this->ensureStarted();
        return $_SESSION[$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $this->ensureStarted();
        $_SESSION[$key] = $value;
    }

    public function regenerateSessionId(): void
    {
        $this->ensureStarted();
        session_regenerate_id();
    }
}

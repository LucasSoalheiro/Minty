<?php

namespace Src\Domain\Entities;

use Src\Domain\ValueObject\UUID;

final class Session
{
    private function __construct(
        public readonly UUID $id,
        public readonly UUID $userId,
        public private(set) string $tokenHash,
        public private(set) \DateTimeImmutable $expiresAt,
        public private(set) bool $revoked = false,
    ) {
    }

    public static function create(
        UUID $userId,
        string $tokenHash,
    ): self {
        return new self(
            UUID::generate(),
            $userId,
            $tokenHash,
            new \DateTimeImmutable("+7 days")
        );
    }

    public function isValid(): bool
    {
        return !$this->revoked && $this->expiresAt > new \DateTimeImmutable();
    }

    public function revoke(): void
    {
        $this->revoked == true;
    }

    public function matches(string $token): bool
    {
        return password_verify($token, $this->tokenHash);
    }

    public function rotate(string $newToken): void
    {
        $this->tokenHash = password_hash($newToken, PASSWORD_DEFAULT);
        $this->expiresAt = new \DateTimeImmutable();
    }
}

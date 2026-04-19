<?php

namespace Src\Domain\Entities;

use Src\Domain\ValueObject\UUID;

final class Session
{
    private function __construct(
        private readonly UUID $id,
        private readonly UUID $userId,
        private string $tokenHash,
        private \DateTimeImmutable $expiresAt,
        private bool $revoked = false,
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

    public function getId(): UUID
    {
        return $this->id;
    }

    public function getUserId(): UUID
    {
        return $this->userId;
    }

    public function getTokenHash(): string
    {
        return $this->tokenHash;
    }

    public function getExpiresAt(): \DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function getRevoked(): bool
    {
        return $this->revoked;
    }
}

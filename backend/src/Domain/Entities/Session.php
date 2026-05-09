<?php

namespace Src\Domain\Entities;

use Src\Domain\Error\InvalidSession;
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
        string $token,
    ): self {
        return new self(
            UUID::generate(),
            $userId,
            self::hashToken($token),
            new \DateTimeImmutable("+7 days")
        );
    }

    public static function restore(
        UUID $id,
        UUID $userId,
        string $tokenHash,
        \DateTimeImmutable $expiresAt,
        bool $revoked
    ): Session {
        return new self(
            $id,
            $userId,
            $tokenHash,
            $expiresAt,
            $revoked
        );
    }

    public function isValid(): bool
    {
        return !$this->revoked && $this->expiresAt > new \DateTimeImmutable();
    }

    public function revoke(): void
    {
        if ($this->revoked) {
            return;
        }

        $this->revoked = true;
    }
    public function matches(string $token): bool
    {
        return hash_equals($this->tokenHash, self::hashToken($token));
    }

    public function rotate(string $newToken): void
    {
        if (!$this->isValid()) {
            throw new InvalidSession("Cannot rotate an invalid session");
        }
        $this->tokenHash = self::hashToken($newToken);
        $this->expiresAt = new \DateTimeImmutable("+7 days");
    }

    private static function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }
}

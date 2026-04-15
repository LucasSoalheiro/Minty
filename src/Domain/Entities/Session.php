<?php

namespace Src\Domain\Entities;

use Src\Domain\ValueObject\UUID;

class Session
{
    private function __construct(
        private readonly UUID $id,
        private readonly UUID $userId,
        private readonly string $tokenHash,
        private readonly \DateTimeImmutable $expiresAt,
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
            new \DateTimeImmutable()
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

    
}

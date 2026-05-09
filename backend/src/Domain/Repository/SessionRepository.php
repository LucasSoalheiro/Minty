<?php

namespace Src\Domain\Repository;

use Src\Domain\Entities\Session;
use Src\Domain\ValueObject\UUID;

interface SessionRepository
{
    public function save(Session $data): void;
    public function findByToken(string $token): ?Session;
    /** @return Session[] */
    public function findByUserId(UUID $userId): array;
}
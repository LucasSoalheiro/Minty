<?php

namespace Tests\fake;

use Src\Domain\Entities\Session;
use Src\Domain\Repository\SessionRepository;
use Src\Domain\ValueObject\UUID;

class FakeSessionRepository implements SessionRepository
{
    private array $sessions = [];
    public function save(Session $data): void
    {
        $this->sessions[] = $data;
    }

    public function findByToken(string $token): ?Session
    {
        foreach ($this->sessions as $session) {
            if ($session->get) {
                return $session;
            }
        }
        return null;
    }
    public function findByUserId(UUID $userId): array
    {
        throw new \Exception('Not implemented');
    }
}
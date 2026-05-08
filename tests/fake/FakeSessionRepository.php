<?php

namespace Tests\fake;

use Src\Domain\Entities\Session;
use Src\Domain\Repository\SessionRepository;
use Src\Domain\ValueObject\UUID;

class FakeSessionRepository implements SessionRepository
{
    /**
     * @var Session[]
     */
    private array $sessions = [];
    public function save(Session $data): void
    {
        $this->sessions[$data->id->__toString()] = $data;
    }

    public function findByToken(string $token): ?Session
    {
        foreach ($this->sessions as $session) {
            if ($session->matches($token)) {
                return $session;
            }
        }
        return null;
    }
    public function findByUserId(UUID $userId): array
    {
        return array_values(array_filter($this->sessions, fn($s) => $s->userId->equals($userId)));
    }
}
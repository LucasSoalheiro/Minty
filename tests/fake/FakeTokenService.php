<?php

namespace Tests\fake;

use Src\App\Security\TokenPayload;
use Src\App\Security\TokenService;
use Src\Domain\User\User;

class FakeTokenService implements TokenService
{
    private array $store = [];

    public function generateToken(User $user): string
    {
        $token = "fake-token-{$user->getId()->__toString()}";

        $this->store[$token] = new TokenPayload($user->getId()->__toString(), [
            'email' => $user->getEmail()->__toString()
        ]);

        return $token;
    }

    public function validateToken(string $token): ?TokenPayload
    {
        return $this->store[$token] ?? null;
    }
}
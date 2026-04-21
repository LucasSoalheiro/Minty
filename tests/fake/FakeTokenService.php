<?php

namespace Tests\fake;

use Src\App\Security\TokenPayload;
use Src\App\Security\TokenService;
use Src\Domain\Entities\User;

final class FakeTokenService implements TokenService
{
    private array $store = [];

    public function generateToken(User $user): string
    {
        $token = "fake-token-{$user->id->__toString()}";

        $this->store[$token] = new TokenPayload($user->id->__toString(), [
            'email' => $user->email->__toString()
        ]);

        return $token;
    }

    public function validateToken(string $token): ?TokenPayload
    {
        return $this->store[$token] ?? null;
    }
}
<?php

namespace Src\App\Security;

use Src\Domain\Entities\User;

interface TokenService
{
    public function generateToken(User $user): string;

    public function validateToken(string $token): ?TokenPayload;
}
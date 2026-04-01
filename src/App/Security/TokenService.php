<?php

namespace Src\App\Security;

use Src\Domain\User\User;


interface TokenService
{
    public function generateToken(User $user): string;

    public function validateToken(string $token): ?TokenPayload;
}
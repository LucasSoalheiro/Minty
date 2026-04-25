<?php

namespace Src\Infra\Http\Security;

use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;
use RuntimeException;
use Src\App\Security\TokenPayload;
use Src\App\Security\TokenService;
use Src\Domain\Entities\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JWT implements TokenService
{
    private string $secret;
    public function __construct(string $secret)
    {
        $this->secret = $_ENV['JWT_SECRET'] != null? $_ENV["JWT_SECRET"] : $secret;
    }
    public function generateToken(User $user): string
    {
        if (empty($this->secret)) {
            throw new HttpException(500, "JWT_SECRET where not loaded");
        }
        $payload = [
            'iss' => "minty",
            'sub' => $user->id->__toString(),
            'iat' => time(),
            'exp' => time() + 900
        ];

        return FirebaseJWT::encode($payload, $this->secret, 'HS256');

    }

    public function validateToken(string $token): ?TokenPayload
    {
        try {
            $decoded = FirebaseJWT::decode($token, new Key($this->secret, 'HS256'));

            if ($decoded->iss != "minty") {
                throw new RuntimeException("Invalid Issuer");
            }
            return new TokenPayload($decoded->sub, [
                'sub' => $decoded->sub,
                'exp' => $decoded->exp
            ]);
        } catch (\Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

}
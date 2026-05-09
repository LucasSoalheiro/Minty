<?php
namespace Tests\fake;

use RuntimeException;
use Src\App\Security\TokenPayload;
use Src\App\Security\TokenService;
use Src\Domain\Entities\User;

final class FakeTokenService implements TokenService
{
    public function generateToken(User $user): string
    {
        $payload = [
            'iss' => 'minty',
            'sub' => $user->id->__toString(),
            'iat' => time(),
            'exp' => time() + 900
        ];

        return base64_encode(json_encode($payload));
    }

    public function validateToken(string $token): ?TokenPayload
    {
        try {
            $decoded = json_decode(base64_decode($token), true);
            
            if (!$decoded) {
                throw new RuntimeException('Invalid token format');
            }

            if (($decoded['iss'] ?? null) !== 'minty') {
                throw new RuntimeException('Invalid issuer');
            }

            if (($decoded['exp'] ?? 0) < time()) {
                throw new RuntimeException('Token expired');
            }
            return new TokenPayload(
                $decoded['sub'],
                [
                    'sub' => $decoded['sub'],
                    'exp' => $decoded['exp']
                ]
            );

        } catch (\Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}
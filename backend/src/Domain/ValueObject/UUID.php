<?php
namespace Src\Domain\ValueObject;

final class UUID
{
    private string $uuid;

    private function __construct(string $uuid)
    {
        if (!self::isValid($uuid)) {
            throw new \InvalidArgumentException("Invalid UUID");
        }

        $this->uuid = $uuid;
    }

    public static function generate(): self
    {
        $data = random_bytes(16);
        $data[6] = \chr((\ord($data[6]) & 0x0f) | 0x40);
        $data[8] = \chr((\ord($data[8]) & 0x3f) | 0x80);

        return new self(vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4)));
    }

    public static function fromString(string $uuid): self
    {
        return new self($uuid);
    }

    public function equals(self $other): bool
    {
        return $this->uuid === $other->uuid;
    }

    public function __toString(): string
    {
        return $this->uuid;
    }

    private static function isValid(string $uuid): bool
    {
        return preg_match(
            '/^[0-9a-fA-F-]{36}$/',
            $uuid
        ) === 1;
    }
}
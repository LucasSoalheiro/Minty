<?php
namespace Src\Domain\Util;

final class UUID
{
    private string $uuid;

    private function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function generate(): UUID
    {
        $data = random_bytes(16);
        $data[6] = \chr((\ord($data[6]) & 0x0f) | 0x40); // version 4
        $data[8] = \chr((\ord($data[8]) & 0x3f) | 0x80); // variant
        return new UUID(vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4)));
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
}
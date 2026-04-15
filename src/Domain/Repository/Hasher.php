<?php
namespace Src\Domain\Repository;

interface Hasher{
    public function hash(string $data): string;
    public function compare(string $data, string $hash): bool;
}
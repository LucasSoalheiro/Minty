<?php
namespace Src\Domain\Category;

interface CategoryRepository
{
    public function save(Category $category): void;
    public function findById(string $id): ?Category;
    public function findAllByUserId(string $userId): array;
    public function findAllByUserIdAndIsActive(string $userId, bool $isActive): array;
}
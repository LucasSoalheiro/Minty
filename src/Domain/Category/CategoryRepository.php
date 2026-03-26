<?php
namespace Src\Domain\Category;

interface CategoryRepository
{
    public function save(Category $category): void;
    public function findById(string $id): ?Category;
    public function findAllByUserId(string $userId): array;
    public function delete(string $id): void;
}
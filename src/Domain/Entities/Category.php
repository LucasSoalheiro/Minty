<?php
namespace Src\Domain\Entities;

use Src\Domain\Error\CategoryInactive;
use Src\Domain\Error\InvalidDescription;
use Src\Domain\Error\NameCannotBeNull;
use Src\Domain\ValueObject\UUID;


final class Category
{
    private function __construct(
        public readonly UUID $id,
        public private(set) string $name {
            set(string $name) {
                if(empty($name)) {
                    throw new NameCannotBeNull();
                }
                $this->name = $name;
            }
        },
        public private(set) ?string $description,
        public readonly UUID $userId,
        public private(set) bool $isActive = true
    ) {
    }

    public static function create(string $name, ?string $description, UUID $userId): Category
    {
        if (empty($name)) {
            throw new NameCannotBeNull();
        }
        return new Category(UUID::generate(), $name, $description, $userId);
    }

    public static function restore(
        UUID $id,
        string $name,
        ?string $description,
        UUID $userId,
        bool $isActive
    ): Category {
        if (empty($name)) {
            throw new NameCannotBeNull();
        }
        return new Category($id, $name, $description, $userId, $isActive);
    }

    public function updateName(string $name): void
    {
        $this->isInactive();
        $this->name = $name;
    }

    public function updateDescription(string $description): void
    {
        $this->isInactive();
        if (!$this->isActive) {
            throw new CategoryInactive();
        }
        if (empty($description)) {
            throw new InvalidDescription();
        }
        $this->description = $description;
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }
    private function isInactive(): void
    {
        if (!$this->isActive) {
            throw new CategoryInactive();
        }
    }

}
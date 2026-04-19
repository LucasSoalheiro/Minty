<?php
namespace Src\Domain\Entities;

use Src\Domain\Error\CategoryInactive;
use Src\Domain\Error\InvalidDescription;
use Src\Domain\Error\NameCannotBeNull;
use Src\Domain\ValueObject\UUID;


final class Category
{
    private function __construct(
        private readonly UUID $id,
        private string $name,
        private ?string $description,
        private readonly UUID $userId,
        private bool $isActive = true
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


    public function getId(): UUID
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getUserId(): UUID
    {
        return $this->userId;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function updateName(string $name): void
    {
        if (!$this->isActive()) {
            throw new CategoryInactive();
        }
        if (empty($name)) {
            throw new NameCannotBeNull();
        }
        $this->name = $name;
    }

    public function updateDescription(string $description): void
    {
        if (!$this->isActive()) {
            throw new CategoryInactive();
        }
        if (empty($description)) {
            throw new InvalidDescription();
        }
        $this->description = $description;
    }

    private function isActive(): bool
    {
        return $this->isActive;
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

}
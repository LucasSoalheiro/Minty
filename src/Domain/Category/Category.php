<?php
namespace Src\Domain\Category;

use Src\Domain\Category\error\InvalidDescription;
use Src\Domain\Category\error\NameCannotBeNull;
use Src\Domain\shared\UUID;

class Category
{
    private readonly UUID $id;
    private function __construct(
        private string $name,
        private ?string $description,
        private readonly UUID $userId
    ) {
        $this->id = UUID::generate();
    }

    public static function create(string $name, ?string $description, UUID  $userId): Category
    {
        if (empty($name)) {
            throw new NameCannotBeNull();
        }
        return new Category($name, $description, $userId);
    }

    public static function restore(string $name, ?string $description, UUID $userId): Category
    {
        if (empty($name)) {
            throw new NameCannotBeNull();
        }
        return new Category($name, $description, $userId);
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

    public function updateName(string $name): void
    {
        if (empty($name)) {
            throw new NameCannotBeNull();
        }
        $this->name = $name;
    }

    public function updateDescription(string $description): void
    {
        if (empty($description)) {
            throw new InvalidDescription();
        }
        $this->description = $description;
    }

}
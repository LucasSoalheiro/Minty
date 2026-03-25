<?php
namespace Src\Domain\Category;

use Src\Domain\shared\UUID;

class Category
{
    private readonly UUID $id;
    private function __construct(
        private readonly string $name,
        private readonly ?string $description,
        private readonly string $userId
    ) {
        $this->id = UUID::generate();
    }

    public static function create(string $name, ?string $description, string $userId): Category
    {
        return new Category($name, $description, $userId);
    }

    public static function restore(string $name, ?string $description, string $userId): Category
    {
        return new Category($name, $description, $userId);
    }


    public function getId(): string
    {
        return $this->id->__toString();
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function updateName(string $name): void
    {
        $this->name = $name;
    }

    public function updateDescription(?string $description): void
    {
        $this->description = $description;
    }

}
<?php

namespace Src\Infra\Http\Schema;

use Src\Infra\Http\Error\ValidatorException;
use Symfony\Component\Validator\Constraints;

class CreateCategorySchema
{
    #[Constraints\NotBlank(message: "Name is required")]
    #[Constraints\Length(min: 3, minMessage: "Name must have at least 3 characters")]
    public string $name;

    #[Constraints\Length(max: 255, maxMessage: "Description cannot be longer than 255 characters")]
    public ?string $description;

    #[Constraints\NotBlank(message: "User ID is required")]
    #[Constraints\Uuid(message: "Invalid UUID format", versions: [4])]
    public string $userId;
    public function __construct(
        ?string $name = "",
        ?string $description = null,
        ?string $userId = ""
    ) {
        if (empty($name) || empty($userId)) {
            throw new ValidatorException("Name and User ID are required");
        }
        $this->name = $name;
        $this->description = $description;
        $this->userId = $userId;
    }
}
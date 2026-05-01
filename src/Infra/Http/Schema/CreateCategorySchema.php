<?php

namespace Src\Infra\Http\Schema;

use Symfony\Component\Validator\Constraints;

class CreateCategorySchema
{
    public function __construct(
        #[Constraints\NotBlank(message: "Name is required")]
        #[Constraints\Length(min: 3, minMessage: "Name must have at least 3 characters")]
        public string $name,

        #[Constraints\Length(max: 255, maxMessage: "Description cannot be longer than 255 characters")]
        public ?string $description,

        #[Constraints\NotBlank(message: "User ID is required")]
        #[Constraints\Uuid(message: "Invalid UUID format", versions: [4])]
        public string $userId,
    ) {
    }
}
<?php

namespace Src\Infra\Http\Schema;

use Symfony\Component\Validator\Constraints;

class CreateAccountSchema
{
    public function __construct(

        #[Constraints\NotBlank(message: "Name is required")]
        #[Constraints\Length(min: 3, minMessage: "Name must have at least 3 characters")]
        public string $name,

        #[Constraints\NotBlank(message: "Balance is required")]
        #[Constraints\PositiveOrZero(message: "Balance must be a positive number or zero")]
        public int $balance,

        #[Constraints\NotBlank(message: "User ID is required")]
        #[Constraints\Uuid(message: "Invalid UUID format", versions: [4])]
        public string $userId,
    ) {
    }
}